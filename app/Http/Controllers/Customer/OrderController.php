<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', \App\Models\Order::class);

        $customer = auth()->user()->customer;

        // SCOPED QUERY: hanya order milik customer ini
        $query = $customer->orders()->with(['shipments'])->withCount('items');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('order_number', 'like', "%{$search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        return view('customer.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $customer = auth()->user()->customer;

        // Ambil order HANYA jika milik customer ini (scoped)
        $order = $customer->orders()
            ->with(['items.product', 'shipments'])
            ->withCount('items')
            ->findOrFail($id);

        // Double-check authorization via policy
        $this->authorize('view', $order);

        return view('customer.orders.show', compact('order'));
    }
}