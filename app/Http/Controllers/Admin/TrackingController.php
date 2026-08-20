<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTrackingUpdateRequest;
use App\Models\Shipment;
use App\Models\TrackingUpdate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    use AuthorizesRequests;

    public function store(StoreTrackingUpdateRequest $request, Shipment $shipment)
    {
        $this->authorize('create', TrackingUpdate::class);

        DB::transaction(function () use ($request, $shipment) {
            $shipment->trackingUpdates()->create([
                'user_id' => auth()->id(),
                'route_point_id' => $request->route_point_id,
                'status' => $request->status,
                'location' => $request->location,
                'description' => $request->description,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'tracked_at' => $request->tracked_at ?? now(),
            ]);

            // Sinkronkan status shipment dengan status update tracking terbaru
            $shipment->update([
                'status' => $request->status,
            ]);

            \Illuminate\Support\Facades\Log::info('Admin: Tracking update created', [
                'admin_id' => auth()->id(),
                'shipment_id' => $shipment->id,
                'status' => $request->status,
                'location' => $request->location,
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

        $trackingUpdate->delete();

        \Illuminate\Support\Facades\Log::info('Admin: Tracking update deleted', [
            'admin_id' => auth()->id(),
            'shipment_id' => $shipment->id,
            'tracking_id' => $trackingId,
        ]);

        return redirect()->route('admin.shipments.show', $shipment)
            ->with('success', 'Update tracking berhasil dihapus.');
    }
}
