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

    public function show(Document $document)
    {
        $this->authorize('view', $document);

        $disk = 'local';
        if (!Storage::disk('local')->exists($document->file_path) && Storage::disk('public')->exists($document->file_path)) {
            $disk = 'public';
        }

        if (!Storage::disk($disk)->exists($document->file_path)) {
            abort(404, 'Dokumen fisik tidak ditemukan.');
        }

        \Illuminate\Support\Facades\Log::info('Document: Document accessed', [
            'user_id' => auth()->id(),
            'document_id' => $document->id,
            'shipment_id' => $document->shipment_id,
            'file_name' => $document->file_name,
        ]);

        return Storage::disk($disk)->response(
            $document->file_path,
            $document->file_name,
            ['Content-Type' => $document->mime_type ?? 'application/octet-stream']
        );
    }

    public function store(StoreDocumentRequest $request, Shipment $shipment)
    {
        $this->authorize('create', Document::class);

        // Simpan file ke private storage (storage/app/private/documents)
        $path = $request->file('file')->store('documents', 'local');

        $document = $shipment->documents()->create([
            'tracking_update_id' => $request->tracking_update_id,
            'user_id' => auth()->id(),
            'type' => $request->type,
            'file_path' => $path,
            'file_name' => $request->file('file')->getClientOriginalName(),
            'mime_type' => $request->file('file')->getMimeType(),
            'file_size' => $request->file('file')->getSize(),
        ]);

        \Illuminate\Support\Facades\Log::info('Admin: Document uploaded', [
            'admin_id' => auth()->id(),
            'shipment_id' => $shipment->id,
            'document_id' => $document->id,
            'type' => $document->type?->value ?? $document->type,
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

        // Hapus file fisik dari storage private atau legacy public
        if ($document->file_path) {
            if (Storage::disk('local')->exists($document->file_path)) {
                Storage::disk('local')->delete($document->file_path);
            } elseif (Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }
        }

        $documentId = $document->id;
        $documentType = $document->type?->value ?? $document->type;

        $document->delete();

        \Illuminate\Support\Facades\Log::info('Admin: Document deleted', [
            'admin_id' => auth()->id(),
            'shipment_id' => $shipment->id,
            'document_id' => $documentId,
            'type' => $documentType,
        ]);

        return redirect()->route('admin.shipments.show', $shipment)
            ->with('success', 'Dokumen berhasil dihapus.');
    }
}