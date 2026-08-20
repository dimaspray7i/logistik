<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCustomerProfileRequest;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $customer = $user->customer;

        // Jika user tidak punya relasi customer, tolak
        if (!$customer) {
            abort(403, 'Akun Anda tidak terhubung ke perusahaan pelanggan.');
        }

        return view('customer.profile.edit', compact('user', 'customer'));
    }

    public function update(UpdateCustomerProfileRequest $request)
    {
        $user = auth()->user();
        $customer = $user->customer;

        if (!$customer) {
            abort(403);
        }

        // 1. Update info perusahaan
        $customer->update([
            'company_name' => $request->company_name,
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
        ]);

        // 2. Update password user (hanya jika diisi)
        if ($request->filled('new_password')) {
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);
        }

        return redirect()->route('customer.profile.edit')
            ->with('success', 'Profil perusahaan berhasil diperbarui.');
    }
}