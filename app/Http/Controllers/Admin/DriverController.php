<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDriverRequest;
use App\Http\Requests\UpdateDriverRequest;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DriverController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Driver::class);

        $query = Driver::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('license_number', 'like', "%{$search}%");
            });
        }

        $drivers = $query->latest()->paginate(10)->withQueryString();

        return view('admin.drivers.index', compact('drivers'));
    }

    public function create()
    {
        $this->authorize('create', Driver::class);
        return view('admin.drivers.create');
    }

    public function store(StoreDriverRequest $request)
    {
        $this->authorize('create', Driver::class);
        Driver::create($request->validated());

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver berhasil ditambahkan.');
    }

    public function edit(Driver $driver)
    {
        $this->authorize('update', $driver);
        return view('admin.drivers.edit', compact('driver'));
    }

    public function update(UpdateDriverRequest $request, Driver $driver)
    {
        $this->authorize('update', $driver);
        $driver->update($request->validated());

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver berhasil diperbarui.');
    }

    public function destroy(Driver $driver)
    {
        $this->authorize('delete', $driver);

        $activeShipments = $driver->shipments()
            ->whereIn('status', ['READY', 'IN_TRANSIT'])
            ->count();

        if ($activeShipments > 0) {
            return redirect()->route('admin.drivers.index')
                ->with('error', 'Driver tidak dapat dihapus karena sedang bertugas di shipment aktif.');
        }

        $driver->delete();

        return redirect()->route('admin.drivers.index')
            ->with('success', 'Driver berhasil dihapus.');
    }
}