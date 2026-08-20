<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(10)->withQueryString();

        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        DB::transaction(function () use ($request) {
            $customer = Customer::create($request->safe()->except(['create_account', 'account_email', 'account_password']));

            \Illuminate\Support\Facades\Log::info('Admin: Customer created', [
                'admin_id' => auth()->id(),
                'customer_id' => $customer->id,
                'company_name' => $customer->company_name,
            ]);

            if ($request->boolean('create_account') && $request->filled('account_email')) {
                User::create([
                    'name' => $request->name,
                    'email' => $request->account_email,
                    'password' => Hash::make($request->account_password),
                    'role' => UserRole::CUSTOMER,
                    'customer_id' => $customer->id,
                ]);
            }
        });

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer berhasil ditambahkan.');
    }

    public function show(Customer $customer)
    {
        $customer->load(['contacts', 'orders', 'shipments', 'user']);
        return view('admin.customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        \Illuminate\Support\Facades\Log::info('Admin: Customer updated', [
            'admin_id' => auth()->id(),
            'customer_id' => $customer->id,
            'company_name' => $customer->company_name,
        ]);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer berhasil diperbarui.');
    }

    public function destroy(Customer $customer)
    {
        if ($customer->user || $customer->orders()->count() > 0 || $customer->shipments()->count() > 0) {
            return redirect()->route('admin.customers.index')
                ->with('error', 'Customer tidak dapat dihapus karena memiliki akun user, order, atau shipment.');
        }

        $customerId = $customer->id;
        $companyName = $customer->company_name;

        $customer->delete();

        \Illuminate\Support\Facades\Log::info('Admin: Customer deleted', [
            'admin_id' => auth()->id(),
            'customer_id' => $customerId,
            'company_name' => $companyName,
        ]);

        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer berhasil dihapus.');
    }
}