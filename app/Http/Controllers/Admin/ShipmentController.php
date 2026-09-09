<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ShipmentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShipmentRequest;
use App\Http\Requests\UpdateShipmentRequest;
use App\Models\Customer;
use App\Models\ExpeditionProvider;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ShipmentController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Shipment::class);

        $query = Shipment::with(['customer', 'expeditionProvider']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('shipment_number', 'like', "%{$search}%")
                  ->orWhere('tracking_number', 'like', "%{$search}%")
                  ->orWhere('carrier', 'like', "%{$search}%")
                  ->orWhere('origin', 'like', "%{$search}%")
                  ->orWhere('destination', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('company_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('expeditionProvider', function ($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%")
                         ->orWhere('code', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            $validStatus = collect(ShipmentStatus::cases())->map(fn($s) => $s->value)->contains($request->status);
            if ($validStatus) {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('shipping_type')) {
            $query->where('shipping_type', $request->shipping_type);
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

        $orders = Order::with(['customer', 'items.product'])
            ->whereIn('status', ['PENDING', 'PROCESSING', 'COMPLETED'])
            ->latest()
            ->get();

        // Load active expedition providers from DB (replaces hard-coded config array)
        $expeditionProviders = ExpeditionProvider::active()->orderBy('name')->get();

        $selectedOrder = null;
        if ($request->filled('order_id')) {
            $selectedOrder = Order::with(['customer', 'items.product'])->find($request->order_id);
        }

        $nextInternalCode = \App\Services\ShipmentCodeGenerator::generate($selectedOrder?->customer);

        return view('admin.shipments.create', compact('orders', 'expeditionProviders', 'nextInternalCode', 'selectedOrder'));
    }

    public function store(StoreShipmentRequest $request)
    {
        $this->authorize('create', Shipment::class);

        DB::transaction(function () use ($request) {
            $order    = Order::with(['customer', 'items'])->findOrFail($request->order_id);
            $customer = $order->customer;

            $shipmentNumber = \App\Services\ShipmentCodeGenerator::generate($customer);

            // All new shipments are EXTERNAL (expedition provider workflow)
            $shippingType = 'EXTERNAL';

            $expeditionProviderId = $request->input('expedition_provider_id');
            $trackingNumber       = $request->input('tracking_number');

            // Backward compat: also store provider code as carrier text
            $providerCode = null;
            if ($expeditionProviderId) {
                $provider    = ExpeditionProvider::find($expeditionProviderId);
                $providerCode = $provider?->code;
            }

            $paymentStatus = $request->input('invoice_payment_status', 'Belum Dibayar');
            $paymentDate   = ($paymentStatus === 'Sudah Dibayar') ? $request->input('invoice_payment_date') : null;

            $shipment = Shipment::create([
                'shipment_number'       => $shipmentNumber,
                'shipping_type'         => $shippingType,
                'carrier'               => $providerCode,
                'tracking_number'       => $trackingNumber,
                'expedition_provider_id'=> $expeditionProviderId,
                'order_id'              => $order->id,
                'customer_id'           => $order->customer_id,
                'vehicle_id'            => null,
                'driver_id'             => null,
                'origin'                => $request->origin,
                'destination'           => $request->destination,
                'departure_date'        => $request->departure_date,
                'estimated_arrival'     => $request->estimated_arrival,
                'total_weight'          => $order->items->sum('weight') ?? 0,
                'status'                => $request->status,
                'notes'                 => $request->notes,
                'invoice_payment_status'=> $paymentStatus,
                'invoice_payment_date'  => $paymentDate,
            ]);

            foreach ($order->items as $item) {
                $shipment->items()->create([
                    'product_id' => $item->product_id,
                    'quantity'   => $item->quantity,
                    'weight'     => $item->weight ?? 0,
                    'unit'       => $item->unit,
                    'notes'      => $item->notes,
                ]);
            }

            \Illuminate\Support\Facades\Log::info('Admin: Shipment created', [
                'admin_id'              => auth()->id(),
                'shipment_id'           => $shipment->id,
                'shipment_number'       => $shipment->shipment_number,
                'shipping_type'         => 'EXTERNAL',
                'expedition_provider_id'=> $expeditionProviderId,
                'tracking_number'       => $shipment->tracking_number,
                'customer_id'           => $shipment->customer_id,
            ]);
        });

        return redirect()->route('admin.shipments.index')
            ->with('success', 'Pengiriman berhasil dibuat.');
    }

    public function show(Shipment $shipment)
    {
        $this->authorize('view', $shipment);

        $shipment->load(['order', 'customer', 'expeditionProvider',
        'items.product', 'route.points', 'trackingUpdates.user',
        'trackingUpdates.routePoint', 'documents']);

        $carrierTrackingService = app(\App\Contracts\CarrierTrackingServiceInterface::class);
        $externalTrackingInfo = $shipment->isExternal() ? $carrierTrackingService->getTrackingDetails($shipment) : null;

        return view('admin.shipments.show', compact('shipment', 'externalTrackingInfo'));
    }

    public function edit(Shipment $shipment)
    {
        $this->authorize('update', $shipment);

        $shipment->load(['order', 'items']);
        $expeditionProviders = ExpeditionProvider::orderBy('is_active', 'desc')->orderBy('name')->get();

        return view('admin.shipments.edit', compact('shipment', 'expeditionProviders'));
    }

    public function update(UpdateShipmentRequest $request, Shipment $shipment)
    {
        $this->authorize('update', $shipment);

        DB::transaction(function () use ($request, $shipment) {
            $shippingType = 'EXTERNAL';

            $expeditionProviderId = $request->input('expedition_provider_id');
            $trackingNumber       = $request->input('tracking_number');

            // Backward compat: store provider code as carrier text
            $providerCode = null;
            if ($expeditionProviderId) {
                $provider    = ExpeditionProvider::find($expeditionProviderId);
                $providerCode = $provider?->code;
            }

            $paymentStatus = $request->input('invoice_payment_status', 'Belum Dibayar');
            $paymentDate   = ($paymentStatus === 'Sudah Dibayar') ? $request->input('invoice_payment_date') : null;

            $shipment->update([
                'shipping_type'          => $shippingType,
                'carrier'                => $providerCode,
                'tracking_number'        => $trackingNumber,
                'expedition_provider_id' => $expeditionProviderId,
                'vehicle_id'             => null,
                'driver_id'              => null,
                'origin'                 => $request->origin,
                'destination'            => $request->destination,
                'departure_date'         => $request->departure_date,
                'estimated_arrival'      => $request->estimated_arrival,
                'actual_arrival'         => $request->actual_arrival,
                'status'                 => $request->status,
                'notes'                  => $request->notes,
                'invoice_payment_status' => $paymentStatus,
                'invoice_payment_date'   => $paymentDate,
            ]);

            \Illuminate\Support\Facades\Log::info('Admin: Shipment updated', [
                'admin_id'       => auth()->id(),
                'shipment_id'    => $shipment->id,
                'shipment_number'=> $shipment->shipment_number,
                'shipping_type'  => 'EXTERNAL',
                'status'         => $shipment->status?->value ?? $shipment->status,
            ]);
        });

        return redirect()->route('admin.shipments.index')
            ->with('success', 'Pengiriman berhasil diperbarui.');
    }

    public function destroy(Shipment $shipment)
    {
        $this->authorize('delete', $shipment);

        if ($shipment->trackingUpdates()->count() > 0) {
            return redirect()->route('admin.shipments.index')
                ->with('error', 'Pengiriman tidak dapat dihapus karena sudah memiliki riwayat tracking.');
        }

        $shipmentId     = $shipment->id;
        $shipmentNumber = $shipment->shipment_number;

        $shipment->delete();

        \Illuminate\Support\Facades\Log::info('Admin: Shipment deleted', [
            'admin_id'        => auth()->id(),
            'shipment_id'     => $shipmentId,
            'shipment_number' => $shipmentNumber,
        ]);

        return redirect()->route('admin.shipments.index')
            ->with('success', 'Pengiriman berhasil dihapus.');
    }
}