<?php

namespace App\Http\Controllers;

use App\Models\FuelStorage;
use App\Models\FuelSupplierTruck;
use App\Models\Site;
use App\Models\Vendor;
use Illuminate\Http\Request;

class FuelStorageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_fuel_storages')->only(['index', 'show']);
        $this->middleware('permission:create_fuel_storages')->only(['create', 'store', 'storeSupplierTruck']);
        $this->middleware('permission:edit_fuel_storages')->only(['edit', 'update']);
        $this->middleware('permission:delete_fuel_storages')->only(['destroy', 'destroySupplierTruck']);
    }

    public function index(Request $request)
    {
        $siteId = $request->get('site_id');
        $storages = FuelStorage::with('site')
            ->when($siteId, fn($q) => $q->where('site_id', $siteId))
            ->orderBy('code')
            ->get();

        $supplierTrucks = FuelSupplierTruck::with(['vendor', 'site'])
            ->when($siteId, fn($q) => $q->where('site_id', $siteId))
            ->orderBy('truck_plat_nomor')
            ->get();

        $sites = Site::orderBy('name')->get();
        $vendors = Vendor::orderBy('name')->get();

        return view('fuel.storages.index', compact('storages', 'supplierTrucks', 'sites', 'vendors', 'siteId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:fuel_storages,code',
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:Main Storage,Fuel Station,Temporary Tank',
            'capacity' => 'required|numeric|min:1',
            'current_stock' => 'nullable|numeric|min:0',
            'min_stock_alert' => 'nullable|numeric|min:0',
            'current_totalizer' => 'nullable|numeric|min:0',
            'site_id' => 'nullable|exists:sites,id',
            'location' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        FuelStorage::create([
            'site_id' => $request->site_id,
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'type' => $request->type,
            'capacity' => $request->capacity,
            'current_stock' => $request->current_stock ?? 0,
            'min_stock_alert' => $request->min_stock_alert ?? 5000,
            'current_totalizer' => $request->current_totalizer ?? 0,
            'location' => $request->location,
            'remarks' => $request->remarks,
            'is_active' => true,
        ]);

        return redirect()->route('fuel.storages.index')->with('success', 'Fuel Storage / Station berhasil ditambahkan.');
    }

    public function show(FuelStorage $storage)
    {
        $storage->load(['site', 'stockLogs' => function($q) {
            $q->orderBy('date_time', 'desc')->orderBy('id', 'desc')->limit(50);
        }]);

        return view('fuel.storages.show', compact('storage'));
    }

    public function update(Request $request, FuelStorage $storage)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:fuel_storages,code,' . $storage->id,
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:Main Storage,Fuel Station,Temporary Tank',
            'capacity' => 'required|numeric|min:1',
            'min_stock_alert' => 'nullable|numeric|min:0',
            'site_id' => 'nullable|exists:sites,id',
            'location' => 'nullable|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        $data = [
            'site_id' => $request->site_id,
            'code' => strtoupper($request->code),
            'name' => $request->name,
            'type' => $request->type,
            'capacity' => $request->capacity,
            'min_stock_alert' => $request->min_stock_alert ?? 5000,
            'location' => $request->location,
            'remarks' => $request->remarks,
        ];

        if (auth()->user()->hasRole('Super Admin')) {
            if ($request->has('current_stock')) {
                $data['current_stock'] = (float) $request->current_stock;
            }
            if ($request->has('current_totalizer')) {
                $data['current_totalizer'] = (float) $request->current_totalizer;
            }
        }

        $storage->update($data);

        return redirect()->route('fuel.storages.index')->with('success', 'Data Fuel Storage berhasil diperbarui.');
    }

    public function destroy(FuelStorage $storage)
    {
        $storage->delete();
        return redirect()->route('fuel.storages.index')->with('success', 'Fuel Storage berhasil dihapus.');
    }

    // Supplier Truck Methods
    public function storeSupplierTruck(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'truck_plat_nomor' => 'required|string|max:50',
            'transportir_name' => 'nullable|string|max:255',
            'driver_name' => 'required|string|max:255',
            'driver_phone' => 'nullable|string|max:50',
            'compartment_capacity' => 'required|numeric|min:1',
            'site_id' => 'nullable|exists:sites,id',
        ]);

        FuelSupplierTruck::create([
            'vendor_id' => $request->vendor_id,
            'site_id' => $request->site_id,
            'truck_plat_nomor' => strtoupper($request->truck_plat_nomor),
            'transportir_name' => $request->transportir_name,
            'driver_name' => $request->driver_name,
            'driver_phone' => $request->driver_phone,
            'compartment_capacity' => $request->compartment_capacity,
            'is_active' => true,
        ]);

        return redirect()->route('fuel.storages.index')->with('success', 'Truk Tangki Supplier berhasil ditambahkan.');
    }

    public function destroySupplierTruck(FuelSupplierTruck $truck)
    {
        $truck->delete();
        return redirect()->route('fuel.storages.index')->with('success', 'Truk Tangki Supplier berhasil dihapus.');
    }
}
