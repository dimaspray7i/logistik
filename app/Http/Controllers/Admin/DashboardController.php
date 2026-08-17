<?php

namespace App\Http\Controllers\Admin;

use App\Enums\ShipmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\TrackingUpdate;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Statistik Global KPI
        $stats = [
            'total_customers' => Customer::count(),
            'total_orders'    => Order::count(),
            'total_shipments' => Shipment::count(),
            'in_transit'      => Shipment::where('status', ShipmentStatus::IN_TRANSIT->value)->count(),
            'delivered'      => Shipment::where('status', ShipmentStatus::DELIVERED->value)->count(),
            'delayed'        => Shipment::where('status', ShipmentStatus::DELAYED->value)->count(),
        ];

        // 2. Recent Shipments (5 terbaru dengan Eager Loading customer & order)
        $recentShipments = Shipment::with(['customer', 'order'])
            ->latest()
            ->take(6)
            ->get();

        // 3. Live Tracking Updates
        $trackingUpdates = TrackingUpdate::with(['shipment.customer'])
            ->latest('tracked_at')
            ->take(5)
            ->get();

        // Jika belum ada TrackingUpdate khusus, ambil shipment aktif sebagai timeline fallback
        $activeShipments = Shipment::with(['customer', 'vehicle', 'driver'])
            ->whereIn('status', [ShipmentStatus::IN_TRANSIT->value, ShipmentStatus::READY->value, ShipmentStatus::DELAYED->value])
            ->latest()
            ->take(5)
            ->get();

        // 4. Monthly Chart Data (6 bulan terakhir)
        $monthlyChartData = collect(range(5, 0))->map(function ($i) {
            $date = now()->subMonths($i);
            $year = $date->year;
            $month = $date->month;
            
            $count = Shipment::whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->count();

            return [
                'label' => $date->format('M Y'),
                'count' => $count,
            ];
        });

        // Hitung max count untuk persentase tinggi bar chart SVG
        $maxChartCount = max(1, $monthlyChartData->max('count'));

        return view('admin.dashboard', compact(
            'stats',
            'recentShipments',
            'trackingUpdates',
            'activeShipments',
            'monthlyChartData',
            'maxChartCount'
        ));
    }
}