<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpeditionProvider;
use Illuminate\Http\Request;

class ExpeditionProviderController extends Controller
{
    public function index()
    {
        $providers = ExpeditionProvider::orderBy('is_active', 'desc')
            ->orderBy('name')
            ->get();

        return view('admin.expedition-providers.index', compact('providers'));
    }

    public function create()
    {
        return view('admin.expedition-providers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'code'        => ['required', 'string', 'max:20', 'unique:expedition_providers,code', 'regex:/^[A-Z0-9\&]+$/i'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active'   => ['nullable', 'boolean'],
        ], [
            'code.unique' => 'Kode ekspedisi sudah digunakan.',
            'code.regex'  => 'Kode hanya boleh berisi huruf, angka, dan &.',
        ]);

        $validated['code']      = strtoupper($validated['code']);
        $validated['is_active'] = $request->boolean('is_active', true);

        ExpeditionProvider::create($validated);

        return redirect()->route('admin.expedition-providers.index')
            ->with('success', 'Ekspedisi "' . $validated['name'] . '" berhasil ditambahkan.');
    }

    public function edit(ExpeditionProvider $expeditionProvider)
    {
        return view('admin.expedition-providers.edit', compact('expeditionProvider'));
    }

    public function update(Request $request, ExpeditionProvider $expeditionProvider)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100'],
            'code'        => ['required', 'string', 'max:20', 'regex:/^[A-Z0-9\&]+$/i', 'unique:expedition_providers,code,' . $expeditionProvider->id],
            'description' => ['nullable', 'string', 'max:255'],
            'is_active'   => ['nullable', 'boolean'],
        ], [
            'code.unique' => 'Kode ekspedisi sudah digunakan.',
        ]);

        $validated['code']      = strtoupper($validated['code']);
        $validated['is_active'] = $request->boolean('is_active', false);

        $expeditionProvider->update($validated);

        return redirect()->route('admin.expedition-providers.index')
            ->with('success', 'Data ekspedisi berhasil diperbarui.');
    }

    public function destroy(ExpeditionProvider $expeditionProvider)
    {
        // Safety check: do not delete if still used by shipments
        if ($expeditionProvider->shipments()->count() > 0) {
            return redirect()->route('admin.expedition-providers.index')
                ->with('error', 'Ekspedisi tidak dapat dihapus karena masih digunakan oleh ' . $expeditionProvider->shipments()->count() . ' pengiriman.');
        }

        $name = $expeditionProvider->name;
        $expeditionProvider->delete();

        return redirect()->route('admin.expedition-providers.index')
            ->with('success', 'Ekspedisi "' . $name . '" berhasil dihapus.');
    }

    /**
     * Toggle active status via AJAX or redirect.
     */
    public function toggleActive(ExpeditionProvider $expeditionProvider)
    {
        $expeditionProvider->update(['is_active' => !$expeditionProvider->is_active]);

        $status = $expeditionProvider->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->route('admin.expedition-providers.index')
            ->with('success', 'Ekspedisi "' . $expeditionProvider->name . '" berhasil ' . $status . '.');
    }
}
