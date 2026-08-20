<?php

namespace App\Http\Controllers\Customer;

use App\Enums\ShipmentStatus;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $customer = $user->customer;

        // Statistik scoped per customer
        $stats = [
            'my_orders'    => $customer->orders()->count(),
            'my_shipments' => $customer->shipments()->count(),
            'in_transit'   => $customer->shipments()->where('status', ShipmentStatus::IN_TRANSIT)->count(),
            'delivered'    => $customer->shipments()->where('status', ShipmentStatus::DELIVERED)->count(),
            'delayed'      => $customer->shipments()->where('status', ShipmentStatus::DELAYED)->count(),
        ];

        $recentShipments = $customer->shipments()->latest()->take(5)->get();

        // Live tracking: shipment IN_TRANSIT terbaru milik customer
        $liveTracking = $customer->shipments()
            ->where('status', ShipmentStatus::IN_TRANSIT)
            ->latest()
            ->first();

        // Grafik riwayat pengiriman 6 bulan terakhir (scoped per customer)
        $monthlyChart = $this->buildMonthlyChart($customer->id);

        return view('customer.dashboard', compact('customer', 'stats', 'recentShipments', 'liveTracking', 'monthlyChart'));
    }

    private function buildMonthlyChart(int $customerId): array
    {
        $months = [];
        $labels = [];

        for ($i = 5; $i >= 0; $i--) {
            $start = now()->subMonths($i)->startOfMonth();
            $end = now()->subMonths($i)->endOfMonth();

            $count = DB::table('shipments')
                ->where('customer_id', $customerId)
                ->whereBetween('created_at', [$start, $end])
                ->count();

            $months[] = $count;
            $labels[] = $start->format('M Y');
        }

        $total = array_sum($months);
        $average = round($total / 6, 1);
        $highest = max($months);
        $currentMonth = $months[5];
        $previousMonth = $months[4];
        $trend = $previousMonth > 0 ? round((($currentMonth - $previousMonth) / $previousMonth) * 100, 0) : 0;

        return [
            'labels' => $labels,
            'data' => $months,
            'total' => $total,
            'average' => $average,
            'highest' => $highest,
            'trend' => $trend,
        ];
    }
}