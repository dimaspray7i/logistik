<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Order::class);

        $query = Order::with(['customer']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('company_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();
        $customers = Customer::orderBy('company_name')->get();

        return view('admin.orders.index', compact('orders', 'customers'));
    }

    public function create()
    {
        $this->authorize('create', Order::class);

        $customers = Customer::orderBy('company_name')->get();
        $products = Product::orderBy('name')->get();

        return view('admin.orders.create', compact('customers', 'products'));
    }

    public function store(StoreOrderRequest $request)
    {
        $this->authorize('create', Order::class);

        DB::transaction(function () use ($request) {
            // Generate order number
            $orderNumber = 'ORD-' . date('Ymd') . '-' . str_pad(Order::count() + 1, 3, '0', STR_PAD_LEFT);

            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_id' => $request->customer_id,
                'order_date' => $request->order_date,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            // Simpan order items
            foreach ($request->items as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'weight' => $item['weight'] ?? 0,
                    'unit' => $item['unit'],
                    'notes' => $item['notes'] ?? null,
                ]);
            }

            \Illuminate\Support\Facades\Log::info('Admin: Order created', [
                'admin_id' => auth()->id(),
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'customer_id' => $order->customer_id,
            ]);
        });

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order berhasil ditambahkan.');
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['customer', 'items.product', 'shipments']);

        return view('admin.orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $this->authorize('update', $order);

        $order->load('items');
        $customers = Customer::orderBy('company_name')->get();
        $products = Product::orderBy('name')->get();

        return view('admin.orders.edit', compact('order', 'customers', 'products'));
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $this->authorize('update', $order);

        DB::transaction(function () use ($request, $order) {
            $order->update([
                'order_date' => $request->order_date,
                'status' => $request->status,
                'notes' => $request->notes,
            ]);

            // Sync order items: update yang ada, tambah baru, hapus yang tidak dikirim
            $existingItemIds = $order->items()->pluck('id')->toArray();
            $incomingItemIds = collect($request->items)->pluck('id')->filter()->toArray();

            // Keamanan: Validasi bahwa semua ID item yang dikirim memang milik order ini (mencegah IDOR item)
            $invalidItemIds = array_diff($incomingItemIds, $existingItemIds);
            if (!empty($invalidItemIds)) {
                abort(403, 'Terdapat item pesanan yang tidak valid untuk pesanan ini.');
            }

            // Hapus item yang tidak ada di request (scoped ke order)
            $toDelete = array_diff($existingItemIds, $incomingItemIds);
            if (!empty($toDelete)) {
                $order->items()->whereIn('id', $toDelete)->delete();
            }

            // Update atau create (scoped ke order)
            foreach ($request->items as $item) {
                if (!empty($item['id'])) {
                    // Update existing scoped ke order ini
                    $order->items()->where('id', $item['id'])->update([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'weight' => $item['weight'] ?? 0,
                        'unit' => $item['unit'],
                        'notes' => $item['notes'] ?? null,
                    ]);
                } else {
                    // Create new
                    $order->items()->create([
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'weight' => $item['weight'] ?? 0,
                        'unit' => $item['unit'],
                        'notes' => $item['notes'] ?? null,
                    ]);
                }
            }

            \Illuminate\Support\Facades\Log::info('Admin: Order updated', [
                'admin_id' => auth()->id(),
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'status' => $order->status?->value ?? $order->status,
            ]);
        });

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order berhasil diperbarui.');
    }

    public function destroy(Order $order)
    {
        $this->authorize('delete', $order);

        if ($order->shipments()->count() > 0) {
            return redirect()->route('admin.orders.index')
                ->with('error', 'Order tidak dapat dihapus karena sudah memiliki shipment.');
        }

        $orderId = $order->id;
        $orderNumber = $order->order_number;

        $order->delete();

        \Illuminate\Support\Facades\Log::info('Admin: Order deleted', [
            'admin_id' => auth()->id(),
            'order_id' => $orderId,
            'order_number' => $orderNumber,
        ]);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order berhasil dihapus.');
    }
}