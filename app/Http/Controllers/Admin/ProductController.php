<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Product::class, 'product');
    }

    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(10)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(StoreProductRequest $request)
    {
        Product::create($request->validated());

        return redirect()->route('admin.products.index')
            ->with('success', 'Product berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('admin.products.edit', compact('product'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->route('admin.products.index')
            ->with('success', 'Product berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->orderItems()->count() > 0 || $product->shipmentItems()->count() > 0) {
            return redirect()->route('admin.products.index')
                ->with('error', 'Product tidak dapat dihapus karena sudah digunakan di order atau shipment.');
        }

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product berhasil dihapus.');
    }
}