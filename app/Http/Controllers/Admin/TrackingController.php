<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTrackingUpdateRequest;
use App\Models\Shipment;
use App\Models\TrackingUpdate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

use App\Services\GeocodingService;

class TrackingController extends Controller
{
    use AuthorizesRequests;

    public function store(StoreTrackingUpdateRequest $request, Shipment $shipment)
    {
        $this->authorize('create', TrackingUpdate::class);

        $lat = $request->latitude ?? $request->lintang;
        $lng = $request->longitude ?? $request->bujur;
        $city = $request->city ?? $request->kota;
        $province = $request->province ?? $request->provinsi;
        $country = $request->country ?? $request->negara;
        $address = $request->address ?? $request->alamat;
        $location = $request->location ?? $request->lokasi;
        $description = $request->description ?? $request->deskripsi;
        $trackedAtRaw = $request->tracked_at ?? $request->dilacak_at;

        // Auto-geocode jika koordinat tidak diisi
        if (is_null($lat) || is_null($lng)) {
            $geocoded = GeocodingService::geocode($location);
            if ($geocoded) {
                $lat = $lat ?? ($geocoded['latitude'] ?? null);
                $lng = $lng ?? ($geocoded['longitude'] ?? null);
                $city = $city ?: ($geocoded['city'] ?? null);
                $province = $province ?: ($geocoded['province'] ?? null);
                $country = $country ?: ($geocoded['country'] ?? null);
            }
        }

        // Aturan Bisnis: Cegah status DELIVERED (Terkirim) dipilih pada titik transit rute yang bukan tujuan
        if ($request->status === 'DELIVERED') {
            if ($request->route_point_id && $shipment->route) {
                $lastRoutePoint = $shipment->route->points()->orderBy('sequence', 'desc')->first();
                if ($lastRoutePoint && $request->route_point_id != $lastRoutePoint->id) {
                    return back()->withInput()->withErrors([
                        'status' => "Status 'Terkirim (DELIVERED)' hanya dapat digunakan untuk titik tujuan akhir, bukan titik transit."
                    ]);
                }
            }
        }

        DB::transaction(function () use ($request, $shipment, $lat, $lng, $city, $province, $country, $address, $location, $description, $trackedAtRaw) {
            $trackedAt = $trackedAtRaw ? \Illuminate\Support\Carbon::parse($trackedAtRaw) : now();

            $data = [
                'user_id' => auth()->id(),
                'route_point_id' => $request->route_point_id,
                'status' => $request->status,
                'location' => $location,
                'description' => !empty($description) && trim($description) !== '' ? trim($description) : '-',
                'latitude' => $lat,
                'longitude' => $lng,
                'tracked_at' => $trackedAt,
            ];

            // Simpan detail lokasi pada kolom masing-masing jika tersedia di database
            if (\Illuminate\Support\Facades\Schema::hasColumn('tracking_updates', 'address')) {
                $data['address'] = $address;
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('tracking_updates', 'city')) {
                $data['city'] = $city;
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('tracking_updates', 'province')) {
                $data['province'] = $province;
            }
            if (\Illuminate\Support\Facades\Schema::hasColumn('tracking_updates', 'country')) {
                $data['country'] = $country;
            }

            $trackingUpdate = $shipment->trackingUpdates()->create($data);

            // Update status route point jika terkait
            if ($request->route_point_id) {
                $rp = \App\Models\RoutePoint::find($request->route_point_id);
                if ($rp && $rp->route_id === ($shipment->route->id ?? null)) {
                    $rpStatus = in_array($request->status, ['ARRIVED', 'DELIVERED']) ? 'ARRIVED' : 'PENDING';
                    $rp->update([
                        'status' => $rpStatus,
                        'actual_arrival' => in_array($request->status, ['ARRIVED', 'DELIVERED']) ? $trackedAt : $rp->actual_arrival,
                    ]);
                }
            }

            // Sinkronkan status shipment berdasarkan update tracking TERBARU secara kronologis
            $latestTracking = $shipment->trackingUpdates()->reorder('tracked_at', 'desc')->orderBy('id', 'desc')->first();
            
            if ($latestTracking) {
                $shipmentStatusVal = is_object($shipment->status) ? $shipment->status->value : $shipment->status;
                
                // Jika shipment sudah DELIVERED dan tracking yang baru ditambahkan adalah log lampau, jangan turunkan status DELIVERED
                if ($shipmentStatusVal === 'DELIVERED' && $latestTracking->status === \App\Enums\ShipmentStatus::DELIVERED) {
                    // Status shipment tetap DELIVERED
                } elseif ($shipmentStatusVal === 'DELIVERED' && $trackedAt->lt($latestTracking->tracked_at) && $request->status !== 'DELIVERED') {
                    // Status shipment tetap DELIVERED
                } else {
                    $updateData = ['status' => $latestTracking->status];
                    if (in_array($latestTracking->status->value ?? $latestTracking->status, ['ARRIVED', 'DELIVERED']) && is_null($shipment->actual_arrival)) {
                        $updateData['actual_arrival'] = $latestTracking->tracked_at;
                    }
                    $shipment->update($updateData);
                }
            }

            // Sinkronkan status Order terkait secara otomatis
            $shipment->syncOrderStatus();

            \Illuminate\Support\Facades\Log::info('Admin: Tracking update created', [
                'admin_id' => auth()->id(),
                'shipment_id' => $shipment->id,
                'status' => $request->status,
                'location' => $location,
                'latitude' => $lat,
                'longitude' => $lng,
            ]);
        });

        return redirect()->route('admin.shipments.show', $shipment)
            ->with('success', 'Update tracking berhasil ditambahkan.');
    }

    public function destroy(Shipment $shipment, TrackingUpdate $trackingUpdate)
    {
        $this->authorize('delete', $trackingUpdate);

        // Pastikan update tracking benar-benar milik shipment ini
        if ($trackingUpdate->shipment_id !== $shipment->id) {
            abort(404, 'Update tracking tidak ditemukan pada pengiriman ini.');
        }

        $trackingId = $trackingUpdate->id;

        DB::transaction(function () use ($shipment, $trackingUpdate) {
            $trackingUpdate->delete();

            // Sinkronkan status shipment dengan update tracking terbaru yang tersisa secara kronologis
            $latestRemaining = $shipment->trackingUpdates()->reorder('tracked_at', 'desc')->orderBy('id', 'desc')->first();
            if ($latestRemaining) {
                $shipment->update(['status' => $latestRemaining->status]);
            } else {
                $fallbackStatus = ($shipment->driver_id || $shipment->vehicle_id) ? \App\Enums\ShipmentStatus::READY : \App\Enums\ShipmentStatus::DRAFT;
                $shipment->update([
                    'status' => $fallbackStatus,
                    'actual_arrival' => null,
                ]);
            }

            // Sinkronkan status Order terkait
            $shipment->syncOrderStatus();
        });

        \Illuminate\Support\Facades\Log::info('Admin: Tracking update deleted', [
            'admin_id' => auth()->id(),
            'shipment_id' => $shipment->id,
            'tracking_id' => $trackingId,
        ]);

        return redirect()->route('admin.shipments.show', $shipment)
            ->with('success', 'Update tracking berhasil dihapus.');
    }
}
