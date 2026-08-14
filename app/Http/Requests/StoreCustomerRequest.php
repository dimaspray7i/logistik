<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Customer::class);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string'],
            
            // Opsi buat akun login sekaligus
            'create_account' => ['sometimes', 'boolean'],
            'account_email' => ['required_if:create_account,1', 'nullable', 'email', 'unique:users,email'],
            'account_password' => ['required_if:create_account,1', 'nullable', 'string', 'min:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'account_email.required_if' => 'Email akun wajib diisi jika membuat akun login.',
            'account_password.required_if' => 'Password akun wajib diisi jika membuat akun login.',
            'account_email.unique' => 'Email akun sudah terdaftar.',
        ];
    }
}