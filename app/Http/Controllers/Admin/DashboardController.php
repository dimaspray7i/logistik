<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ShipmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik Global
        $stats = [
            'total_customers' => Customer::count(),
            'total_orders' => Order::count(),
            'total_shipments' => Shipment::count(),
            'in_transit' => Shipment::where('status', ShipmentStatus::IN_TRANSIT->value)->count(),
            'delivered' => Shipment::where('status', ShipmentStatus::DELIVERED->value)->count(),
            'delayed' => Shipment::where('status', ShipmentStatus::DELAYED->value)->count(),
        ];

        //  Recent Shipments (5 terbaru) + Eager Loading Customer agar query efisien
        $recentShipments = Shipment::with(['customer'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentShipments'));
    }
}