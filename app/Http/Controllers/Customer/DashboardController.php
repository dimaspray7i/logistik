<?php

namespace App\Http\Controllers\Customer;

use App\Enums\ShipmentStatus;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $customer = $user->customer;

        $stats = [
            'my_orders'    => $customer->orders()->count(),
            'my_shipments' => $customer->shipments()->count(),
            'in_transit'   => $customer->shipments()->where('status', ShipmentStatus::IN_TRANSIT)->count(),
            'delivered'    => $customer->shipments()->where('status', ShipmentStatus::DELIVERED)->count(),
            'delayed'      => $customer->shipments()->where('status', ShipmentStatus::DELAYED)->count(),
        ];

        $recentShipments = $customer->shipments()
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard', compact('customer', 'stats', 'recentShipments'));
    }
}