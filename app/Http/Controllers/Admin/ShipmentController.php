<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ShipmentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShipmentRequest;
use App\Http\Requests\UpdateShipmentRequest;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ShipmentController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Shipment::class);

        $query = Shipment::with(['customer', 'vehicle', 'driver']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('shipment_number', 'like', "%{$search}%")
                  ->orWhere('origin', 'like', "%{$search}%")
                  ->orWhere('destination', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('company_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            // Validate that the status value is a valid ShipmentStatus enum before filtering
            $validStatus = collect(ShipmentStatus::cases())->map(fn($s) => $s->value)->contains($request->status);
            if ($validStatus) {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $shipments = $query->latest()->paginate(10)->withQueryString();
        $customers = Customer::orderBy('company_name')->get();

        return view('admin.shipments.index', compact('shipments', 'customers'));
    }

    public function create(Request $request)
    {
        $this->authorize('create', Shipment::class);

        // Ambil order yang belum punya shipment atau masih bisa ditambah shipment-nya
        $orders = Order::with(['customer', 'items.product'])
            ->whereIn('status', ['PENDING', 'PROCESSING', 'COMPLETED'])
            ->latest()
            ->get();

        $vehicles = Vehicle::where('status', 'AVAILABLE')->orWhere('status', 'IN_USE')->get();
        $drivers = Driver::where('status', 'ACTIVE')->get();

        // Jika ada query parameter order_id, pre-select order tersebut
        $selectedOrder = null;
        if ($request->filled('order_id')) {
            $selectedOrder = Order::with(['customer', 'items.product'])->find($request->order_id);
        }

        return view('admin.shipments.create', compact('orders', 'vehicles', 'drivers', 'selectedOrder'));
    }

    public function store(StoreShipmentRequest $request)
    {
        $this->authorize('create', Shipment::class);

        DB::transaction(function () use ($request) {
            $order = Order::with('items')->findOrFail($request->order_id);

            // Generate shipment number
            $shipmentNumber = 'SHP-' . date('Ymd') . '-' . str_pad(Shipment::count() + 1, 3, '0', STR_PAD_LEFT);

            $paymentStatus = $request->input('invoice_payment_status', 'Belum Dibayar');
            $paymentDate = ($paymentStatus === 'Sudah Dibayar') ? $request->input('invoice_payment_date') : null;

            $shipment = Shipment::create([
                'shipment_number' => $shipmentNumber,
                'order_id' => $order->id,
                'customer_id' => $order->customer_id,
                'vehicle_id' => $request->vehicle_id,
                'driver_id' => $request->driver_id,
                'origin' => $request->origin,
                'destination' => $request->destination,
                'departure_date' => $request->departure_date,
                'estimated_arrival' => $request->estimated_arrival,
                'total_weight' => $order->items->sum('weight') ?? 0,
                'status' => $request->status,
                'notes' => $request->notes,
                'invoice_payment_status' => $paymentStatus,
                'invoice_payment_date' => $paymentDate,
            ]);

            // Copy order items ke shipment items
            foreach ($order->items as $item) {
                $shipment->items()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'weight' => $item->weight ?? 0,
                    'unit' => $item->unit,
                    'notes' => $item->notes,
                ]);
            }

            // Update status vehicle jika di-assign
            if ($request->vehicle_id && in_array($request->status, ['READY', 'IN_TRANSIT'])) {
                Vehicle::where('id', $request->vehicle_id)->update(['status' => 'IN_USE']);
            }

            \Illuminate\Support\Facades\Log::info('Admin: Shipment created', [
                'admin_id' => auth()->id(),
                'shipment_id' => $shipment->id,
                'shipment_number' => $shipment->shipment_number,
                'customer_id' => $shipment->customer_id,
            ]);
        });

        return redirect()->route('admin.shipments.index')
            ->with('success', 'Pengiriman berhasil dibuat.');
    }

    public function show(Shipment $shipment)
    {
        $this->authorize('view', $shipment);

        $shipment->load(['order', 'customer', 'vehicle', 'driver', 
        'items.product', 'route.points', 'trackingUpdates.user', 
        'trackingUpdates.routePoint', 'documents']);

        return view('admin.shipments.show', compact('shipment'));
    }

    public function edit(Shipment $shipment)
    {
        $this->authorize('update', $shipment);

        $shipment->load(['order', 'items']);
        $vehicles = Vehicle::all();
        $drivers = Driver::all();

        return view('admin.shipments.edit', compact('shipment', 'vehicles', 'drivers'));
    }

    public function update(UpdateShipmentRequest $request, Shipment $shipment)
    {
        $this->authorize('update', $shipment);

        DB::transaction(function () use ($request, $shipment) {
            $oldVehicleId = $shipment->vehicle_id;
            $newVehicleId = $request->vehicle_id;

            $paymentStatus = $request->input('invoice_payment_status', 'Belum Dibayar');
            $paymentDate = ($paymentStatus === 'Sudah Dibayar') ? $request->input('invoice_payment_date') : null;

            $shipment->update([
                'vehicle_id' => $request->vehicle_id,
                'driver_id' => $request->driver_id,
                'origin' => $request->origin,
                'destination' => $request->destination,
                'departure_date' => $request->departure_date,
                'estimated_arrival' => $request->estimated_arrival,
                'actual_arrival' => $request->actual_arrival,
                'status' => $request->status,
                'notes' => $request->notes,
                'invoice_payment_status' => $paymentStatus,
                'invoice_payment_date' => $paymentDate,
            ]);

            // Update status vehicle lama kembali AVAILABLE jika sudah tidak dipakai
            if ($oldVehicleId && $oldVehicleId != $newVehicleId) {
                $stillUsed = Shipment::where('vehicle_id', $oldVehicleId)
                    ->whereIn('status', ['READY', 'IN_TRANSIT'])
                    ->where('id', '!=', $shipment->id)
                    ->exists();
                if (!$stillUsed) {
                    Vehicle::where('id', $oldVehicleId)->update(['status' => 'AVAILABLE']);
                }
            }

            // Update status vehicle baru menjadi IN_USE jika aktif
            if ($newVehicleId && in_array($request->status, ['READY', 'IN_TRANSIT'])) {
                Vehicle::where('id', $newVehicleId)->update(['status' => 'IN_USE']);
            }

            \Illuminate\Support\Facades\Log::info('Admin: Shipment updated', [
                'admin_id' => auth()->id(),
                'shipment_id' => $shipment->id,
                'shipment_number' => $shipment->shipment_number,
                'status' => $shipment->status?->value ?? $shipment->status,
            ]);
        });

        return redirect()->route('admin.shipments.index')
            ->with('success', 'Pengiriman berhasil diperbarui.');
    }

    public function destroy(Shipment $shipment)
    {
        $this->authorize('delete', $shipment);

        // Jangan hapus jika sudah ada tracking update
        if ($shipment->trackingUpdates()->count() > 0) {
            return redirect()->route('admin.shipments.index')
                ->with('error', 'Pengiriman tidak dapat dihapus karena sudah memiliki riwayat tracking.');
        }

        // Kembalikan status vehicle jika di-assign
        if ($shipment->vehicle_id) {
            $stillUsed = Shipment::where('vehicle_id', $shipment->vehicle_id)
                ->whereIn('status', ['READY', 'IN_TRANSIT'])
                ->where('id', '!=', $shipment->id)
                ->exists();
            if (!$stillUsed) {
                Vehicle::where('id', $shipment->vehicle_id)->update(['status' => 'AVAILABLE']);
            }
        }

        $shipmentId = $shipment->id;
        $shipmentNumber = $shipment->shipment_number;

        $shipment->delete();

        \Illuminate\Support\Facades\Log::info('Admin: Shipment deleted', [
            'admin_id' => auth()->id(),
            'shipment_id' => $shipmentId,
            'shipment_number' => $shipmentNumber,
        ]);

        return redirect()->route('admin.shipments.index')
            ->with('success', 'Pengiriman berhasil dihapus.');
    }
}