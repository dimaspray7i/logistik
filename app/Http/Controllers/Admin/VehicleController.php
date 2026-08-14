<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class VehicleController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', Vehicle::class);

        $query = Vehicle::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('plate_number', 'like', "%{$search}%")
                  ->orWhere('vehicle_type', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        $vehicles = $query->latest()->paginate(10)->withQueryString();

        return view('admin.vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        $this->authorize('create', Vehicle::class);
        return view('admin.vehicles.create');
    }

    public function store(StoreVehicleRequest $request)
    {
        $this->authorize('create', Vehicle::class);
        Vehicle::create($request->validated());

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle berhasil ditambahkan.');
    }

    public function edit(Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);
        return view('admin.vehicles.edit', compact('vehicle'));
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle)
    {
        $this->authorize('update', $vehicle);
        $vehicle->update($request->validated());

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle berhasil diperbarui.');
    }

    public function destroy(Vehicle $vehicle)
    {
        $this->authorize('delete', $vehicle);

        $activeShipments = $vehicle->shipments()
            ->whereIn('status', ['READY', 'IN_TRANSIT'])
            ->count();

        if ($activeShipments > 0) {
            return redirect()->route('admin.vehicles.index')
                ->with('error', 'Vehicle tidak dapat dihapus karena sedang digunakan di shipment aktif.');
        }

        $vehicle->delete();

        return redirect()->route('admin.vehicles.index')
            ->with('success', 'Vehicle berhasil dihapus.');
    }
}