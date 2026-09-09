<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use Illuminate\Http\Request;

class PublicTrackingController extends Controller
{
    /**
     * Show the public tracking form.
     */
    public function index()
    {
        return view('public.tracking.index');
    }

    /**
     * Handle tracking form submission — redirect to GET show.
     */
    public function search(Request $request)
    {
        $trackingNum = $request->input('tracking_number') ?: $request->input('resi');
        if ($trackingNum) {
            $request->merge(['tracking_number' => $trackingNum]);
        }

        $request->validate([
            'tracking_number' => ['required', 'string', 'max:100', 'regex:/^[A-Za-z0-9\-\_]+$/'],
        ], [
            'tracking_number.required' => 'Nomor resi wajib diisi.',
            'tracking_number.regex'    => 'Format nomor resi tidak valid. Hanya huruf, angka, tanda - dan _ yang diperbolehkan.',
            'tracking_number.max'      => 'Nomor resi terlalu panjang.',
        ]);

        return redirect()->route('tracking.show', [
            'trackingNumber' => strtoupper(trim($request->tracking_number)),
        ]);
    }

    /**
     * Show public tracking result.
     * Only exposes safe, non-sensitive data to the public.
     */
    public function show(string $trackingNumber)
    {
        // Security: search by tracking_number only (not by internal ID)
        $shipment = Shipment::with([
            'expeditionProvider',
            'trackingUpdates' => fn($q) => $q->orderBy('tracked_at', 'asc'),
        ])
        ->where('tracking_number', strtoupper($trackingNumber))
        ->first();

        // If not found, try case-insensitive fallback
        if (!$shipment) {
            $shipment = Shipment::with([
                'expeditionProvider',
                'trackingUpdates' => fn($q) => $q->orderBy('tracked_at', 'asc'),
            ])
            ->whereRaw('UPPER(tracking_number) = ?', [strtoupper($trackingNumber)])
            ->first();
        }

        if (!$shipment) {
            return view('public.tracking.index', [
                'notFound'       => true,
                'searchedNumber' => $trackingNumber,
            ]);
        }

        // Build safe public data (no sensitive customer info)
        $publicData = $this->buildPublicData($shipment);

        return view('public.tracking.show', [
            'shipment'   => $shipment,
            'publicData' => $publicData,
        ]);
    }

    /**
     * Build safe, sanitized data for public display.
     * Deliberately excludes: customer email, phone, address, financial data, internal IDs.
     */
    private function buildPublicData(Shipment $shipment): array
    {
        return [
            'tracking_number'   => $shipment->tracking_number,
            'shipment_number'   => $shipment->shipment_number,
            'expedition_type'   => 'Ekspedisi Eksternal',
            'provider_name'     => $shipment->carrier_label,
            'provider_code'     => $shipment->provider_code,
            'status'            => $shipment->status,
            'status_label'      => $shipment->status?->label() ?? ucfirst(strtolower($shipment->status ?? '')),
            'origin'            => $shipment->origin,
            'destination'       => $shipment->destination,
            'departure_date'    => $shipment->departure_date,
            'estimated_arrival' => $shipment->estimated_arrival,
            'actual_arrival'    => $shipment->actual_arrival,
            'tracking_updates'  => $shipment->trackingUpdates->map(fn($u) => [
                'status'      => $u->status,
                'status_label'=> $u->status?->label() ?? ucfirst(strtolower($u->status ?? '')),
                'description' => $u->description,
                'location'    => $u->location,
                'tracked_at'  => $u->tracked_at,
                'notes'       => $u->notes,
            ])->values(),
        ];
    }
}
