<?php

namespace App\Http\Controllers;

use App\Models\FuelStorage;
use App\Models\FuelTruck;
use App\Models\FuelTruckFilling;
use App\Models\Site;
use App\Services\FuelStockService;
use Illuminate\Http\Request;

class FuelTruckFillingController extends Controller
{
    protected FuelStockService $stockService;

    public function __construct(FuelStockService $stockService)
    {
        $this->stockService = $stockService;
        $this->middleware('permission:view_fuel_truck_fillings')->only(['index', 'show']);
        $this->middleware('permission:create_fuel_truck_fillings')->only(['create', 'store']);
    }

    public function index(Request $request)
    {
        $siteId = $request->get('site_id');
        $truckId = $request->get('fuel_truck_id');
        $shift = $request->get('shift');

        $query = FuelTruckFilling::with(['storage', 'fuelTruck.masterUnit', 'creator', 'site']);

        if ($siteId) $query->where('site_id', $siteId);
        if ($truckId) $query->where('fuel_truck_id', $truckId);
        if ($shift) $query->where('shift', $shift);

        $fillings = $query->orderBy('fill_date', 'desc')->paginate(15)->withQueryString();

        $sites = Site::orderBy('name')->get();
        $fuelTrucks = FuelTruck::with('masterUnit')->where('is_active', true)->get();

        return view('fuel.truck_fillings.index', compact('fillings', 'sites', 'fuelTrucks', 'siteId', 'truckId', 'shift'));
    }

    public function create()
    {
        $storages = FuelStorage::where('is_active', true)->orderBy('code')->get();
        $fuelTrucks = FuelTruck::with('masterUnit')->where('is_active', true)->get();
        $autoNumber = FuelTruckFilling::generateRefillNumber();

        return view('fuel.truck_fillings.create', compact('storages', 'fuelTrucks', 'autoNumber'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fuel_storage_id' => 'required|exists:fuel_storages,id',
            'fuel_truck_id' => 'required|exists:fuel_trucks,id',
            'fill_date' => 'required|date',
            'shift' => 'required|in:Shift 1,Shift 2',
            'volume_liters' => 'required|numeric|min:1',
            'storage_totalizer_before' => 'nullable|numeric|min:0',
            'storage_totalizer_after' => 'nullable|numeric|min:0',
            'driver_fuel_truck' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $storage = FuelStorage::findOrFail($request->fuel_storage_id);

        try {
            $filling = FuelTruckFilling::create([
                'refill_number' => FuelTruckFilling::generateRefillNumber(),
                'site_id' => $storage->site_id,
                'fuel_storage_id' => $request->fuel_storage_id,
                'fuel_truck_id' => $request->fuel_truck_id,
                'fill_date' => $request->fill_date,
                'shift' => $request->shift,
                'storage_totalizer_before' => $request->storage_totalizer_before,
                'storage_totalizer_after' => $request->storage_totalizer_after,
                'volume_liters' => $request->volume_liters,
                'driver_fuel_truck' => $request->driver_fuel_truck,
                'notes' => $request->notes,
                'created_by' => auth()->id(),
            ]);

            $this->stockService->executeTruckFilling($filling, auth()->id());

            return redirect()->route('fuel.truck-fillings.index')->with('success', "Pengisian Fuel Truck {$filling->refill_number} berhasil disimpan.");
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Gagal memproses pengisian Fuel Truck: ' . $e->getMessage());
        }
    }

    public function destroy(FuelTruckFilling $truck_filling)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            return back()->with('error', 'Hanya Super Admin yang dapat membatalkan dan menghapus transaksi pengisian Fuel Truck.');
        }

        try {
            $refNum = $truck_filling->refill_number;
            $this->stockService->rollbackAndForceDeleteTruckFilling($truck_filling, auth()->id());
            return redirect()->route('fuel.truck-fillings.index')->with('success', "Transaksi pengisian Fuel Truck {$refNum} berhasil dibatalkan dan stok tangki/truk telah dikembalikan.");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membatalkan transaksi pengisian: ' . $e->getMessage());
        }
    }
}
