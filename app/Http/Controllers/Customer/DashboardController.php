<?php

namespace App\Http\Controllers\Customer;

use App\Enums\ShipmentStatus;
use App\Http\Controllers\Controller;
use App\Models\TrackingUpdate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user     = auth()->user();
        $customer = $user->customer;

        // ---------------------------------------------------------------
        // KPI — semua query terisolasi ke customer login (server-side)
        // ---------------------------------------------------------------
        $totalOrders    = $customer->orders()->count();
        $totalShipments = $customer->shipments()->count();
        $inTransit      = $customer->shipments()->where('status', ShipmentStatus::IN_TRANSIT)->count();
        $delivered      = $customer->shipments()->where('status', ShipmentStatus::DELIVERED)->count();
        $delayed        = $customer->shipments()->where('status', ShipmentStatus::DELAYED)->count();
        $totalWeight    = $customer->shipments()->sum('total_weight');

        $stats = [
            'my_orders'      => $totalOrders,
            'my_shipments'   => $totalShipments,
            'in_transit'     => $inTransit,
            'delivered'      => $delivered,
            'delayed'        => $delayed,
            'total_weight'   => $totalWeight,
        ];

        // ---------------------------------------------------------------
        // Recent Shipments — eager-load route untuk origin/destination
        // ---------------------------------------------------------------
        $recentShipments = $customer->shipments()
            ->with(['route.points'])
            ->latest()
            ->take(6)
            ->get();

        // ---------------------------------------------------------------
        // Recent Orders — order terbaru customer ini
        // ---------------------------------------------------------------
        $recentOrders = $customer->orders()
            ->withCount('items')
            ->latest('order_date')
            ->take(5)
            ->get();

        // ---------------------------------------------------------------
        // Live Tracking — tracking update dari shipment milik customer ini
        // Subquery memastikan tracking tidak bocor ke customer lain
        // ---------------------------------------------------------------
        $customerShipmentIds = $customer->shipments()->pluck('id');

        $recentTracking = TrackingUpdate::whereIn('shipment_id', $customerShipmentIds)
            ->with(['shipment'])
            ->latest('tracked_at')
            ->take(6)
            ->get();

        // ---------------------------------------------------------------
        // Monthly Chart — 6 bulan terakhir, terisolasi ke customer ini
        // ---------------------------------------------------------------
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date  = Carbon::now()->subMonths($i);
            $count = $customer->shipments()
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $monthlyData[] = [
                'month' => $date->locale('id')->isoFormat('MMM'),
                'year'  => $date->year,
                'count' => $count,
            ];
        }
        $chartMax = max(array_column($monthlyData, 'count') ?: [1]);

        return view('customer.dashboard', compact(
            'customer',
            'stats',
            'recentShipments',
            'recentOrders',
            'recentTracking',
            'monthlyData',
            'chartMax'
        ));
    }
}