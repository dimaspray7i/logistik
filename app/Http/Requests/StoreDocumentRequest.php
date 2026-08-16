<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Document::class);
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'], // maks 5MB
            'title' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:RESI,SURAT_JALAN,FOTO_BARANG,LAINNYA'],
            'tracking_update_id' => ['nullable', 'exists:tracking_updates,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.mimes' => 'File harus berupa PDF, JPG, atau PNG.',
            'file.max' => 'Ukuran file maksimal 5MB.',
        ];
    }
}