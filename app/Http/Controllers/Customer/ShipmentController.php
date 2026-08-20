<?php

namespace App\Http\Controllers\Customer;

use App\Enums\ShipmentStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShipmentController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', \App\Models\Shipment::class);

        $customer = auth()->user()->customer;

        // SCOPED QUERY: hanya shipment milik customer ini
        $query = $customer->shipments()->with(['vehicle', 'driver']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('shipment_number', 'like', "%{$search}%")
                  ->orWhere('origin', 'like', "%{$search}%")
                  ->orWhere('destination', 'like', "%{$search}%");
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $shipments = $query->latest()->paginate(10)->withQueryString();

        return view('customer.shipments.index', compact('shipments'));
    }

    public function show($id)
    {
        $customer = auth()->user()->customer;

        // Ambil shipment HANYA jika milik customer ini (scoped)
        $shipment = $customer->shipments()
            ->with(['order', 'vehicle', 'driver', 'items.product', 'route.points', 'trackingUpdates.user', 'documents'])
            ->findOrFail($id);

        // Double-check authorization via policy
        $this->authorize('view', $shipment);

        return view('customer.shipments.show', compact('shipment'));
    }
}