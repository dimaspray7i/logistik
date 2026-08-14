<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('driver'));
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'license_number' => ['nullable', 'string', 'max:50'],
            'status' => ['required', 'string', 'in:ACTIVE,INACTIVE'],
        ];
    }
}