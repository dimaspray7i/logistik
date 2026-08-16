<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRouteRequest;
use App\Models\Route;
use App\Models\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RouteController extends Controller
{
    use AuthorizesRequests;

    // Tampilkan form rute untuk sebuah shipment
    public function edit(Shipment $shipment)
    {
        $this->authorize('update', Route::class);

        $shipment->load(['route.points', 'customer']);

        return view('admin.routes.edit', compact('shipment'));
    }

    // Simpan atau perbarui rute
    public function store(StoreRouteRequest $request, Shipment $shipment)
    {
        $this->authorize('create', Route::class);

        DB::transaction(function () use ($request, $shipment) {
            // Ambil atau buat route untuk shipment ini
            $route = $shipment->route ?? $shipment->route()->create([
                'distance' => $request->distance,
                'duration' => $request->duration,
            ]);

            // Update distance & duration jika route sudah ada
            $route->update([
                'distance' => $request->distance,
                'duration' => $request->duration,
            ]);

            // Hapus semua titik lama, lalu buat ulang sesuai urutan baru
            $route->points()->delete();

            foreach ($request->points as $index => $point) {
                $route->points()->create([
                    'sequence' => $index + 1,
                    'location_name' => $point['location_name'],
                    'address' => $point['address'] ?? null,
                    'latitude' => $point['latitude'] ?? null,
                    'longitude' => $point['longitude'] ?? null,
                    'estimated_arrival' => $point['estimated_arrival'] ?? null,
                    'status' => 'PENDING',
                ]);
            }
        });

        return redirect()->route('admin.shipments.show', $shipment)
            ->with('success', 'Rute pengiriman berhasil disimpan.');
    }
}