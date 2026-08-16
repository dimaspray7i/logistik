<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDocumentRequest;
use App\Models\Document;
use App\Models\Shipment;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    use AuthorizesRequests;

    public function store(StoreDocumentRequest $request, Shipment $shipment)
    {
        $this->authorize('create', Document::class);

        // Simpan file ke storage/app/public/documents
        $path = $request->file('file')->store('documents', 'public');

        $shipment->documents()->create([
            'tracking_update_id' => $request->tracking_update_id,
            'user_id' => auth()->id(),
            'title' => $request->title,
            'type' => $request->type,
            'file_path' => $path,
            'file_name' => $request->file('file')->getClientOriginalName(),
            'mime_type' => $request->file('file')->getMimeType(),
            'size' => $request->file('file')->getSize(),
        ]);

        return redirect()->route('admin.shipments.show', $shipment)
            ->with('success', 'Dokumen berhasil diunggah.');
    }

    public function destroy(Shipment $shipment, Document $document)
    {
        $this->authorize('delete', $document);

        // Keamanan: pastikan dokumen milik shipment ini
        if ($document->shipment_id !== $shipment->id) {
            abort(404);
        }

        // Hapus file fisik dari storage
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('admin.shipments.show', $shipment)
            ->with('success', 'Dokumen berhasil dihapus.');
    }
}