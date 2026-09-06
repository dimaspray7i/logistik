<?php

namespace App\Http\Requests;

use App\Models\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        $customer = $this->route('customer');
        if (! $customer instanceof Customer) {
            $customer = Customer::find($customer);
        }

        return $customer ? $this->user()->can('update', $customer) : false;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('shipment_code_prefix')) {
            $rawPrefix = $this->input('shipment_code_prefix');
            if ($rawPrefix === null || trim((string) $rawPrefix) === '') {
                $this->merge(['shipment_code_prefix' => null]);
            } else {
                $sanitized = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', (string) $rawPrefix));
                $this->merge(['shipment_code_prefix' => $sanitized !== '' ? $sanitized : null]);
            }
        }
    }

    public function rules(): array
    {
        $customer = $this->route('customer');
        $customerId = $customer instanceof Customer ? $customer->id : $customer;

        return [
            'name' => ['required', 'string', 'max:255'],
            'company_name' => ['required', 'string', 'max:255'],
            'shipment_code_prefix' => [
                'nullable',
                'string',
                'alpha_num',
                'min:2',
                'max:10',
                Rule::unique('customers', 'shipment_code_prefix')->ignore($customerId),
            ],
            'phone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'province' => ['required', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'shipment_code_prefix.alpha_num' => 'Prefix Kode Pengiriman Internal hanya boleh berisi huruf dan angka tanpa spasi.',
            'shipment_code_prefix.unique' => 'Prefix Kode Pengiriman Internal sudah digunakan oleh pelanggan lain.',
            'shipment_code_prefix.min' => 'Prefix Kode Pengiriman Internal minimal 2 karakter.',
            'shipment_code_prefix.max' => 'Prefix Kode Pengiriman Internal maksimal 10 karakter.',
        ];
    }
}