<?php

namespace App\Http\Controllers;

use App\Models\FuelTruck;
use App\Models\MasterUnit;
use App\Models\Site;
use Illuminate\Http\Request;

class FuelTruckController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_fuel_trucks')->only(['index', 'show']);
        $this->middleware('permission:create_fuel_trucks')->only(['create', 'store']);
        $this->middleware('permission:edit_fuel_trucks')->only(['edit', 'update']);
        $this->middleware('permission:delete_fuel_trucks')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $siteId = $request->get('site_id');
        $fuelTrucks = FuelTruck::with(['masterUnit', 'site'])
            ->when($siteId, fn($q) => $q->where('site_id', $siteId))
            ->get();

        // Get Available Master Units that are not yet assigned as Fuel Truck
        $assignedUnitIds = FuelTruck::pluck('master_unit_id')->toArray();
        $availableUnits = MasterUnit::whereNotIn('id', $assignedUnitIds)
            ->where('active', 1)
            ->when($siteId, fn($q) => $q->where('site_id', $siteId))
            ->orderBy('nomor_unit')
            ->get();

        $sites = Site::orderBy('name')->get();

        return view('fuel.trucks.index', compact('fuelTrucks', 'availableUnits', 'sites', 'siteId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'master_unit_id' => 'required|exists:master_units,id|unique:fuel_trucks,master_unit_id',
            'capacity' => 'required|numeric|min:1',
            'current_stock' => 'nullable|numeric|min:0',
            'initial_totalizer' => 'nullable|numeric|min:0',
            'flowmeter_serial_number' => 'nullable|string|max:100',
            'dispenser_brand' => 'nullable|string|max:100',
            'site_id' => 'nullable|exists:sites,id',
        ]);

        $unit = MasterUnit::find($request->master_unit_id);
        $initTot = $request->initial_totalizer ?? 0;

        FuelTruck::create([
            'master_unit_id' => $request->master_unit_id,
            'site_id' => $request->site_id ?: ($unit ? $unit->site_id : null),
            'capacity' => $request->capacity,
            'current_stock' => $request->current_stock ?? 0,
            'initial_totalizer' => $initTot,
            'current_totalizer' => $initTot,
            'flowmeter_serial_number' => $request->flowmeter_serial_number,
            'dispenser_brand' => $request->dispenser_brand,
            'is_active' => true,
        ]);

        return redirect()->route('fuel.trucks.index')->with('success', 'Unit Fuel Truck berhasil ditetapkan dan didaftarkan.');
    }

    public function show(FuelTruck $truck)
    {
        $truck->load(['masterUnit', 'site', 'distributionShifts' => function($q) {
            $q->orderBy('date', 'desc')->limit(20);
        }, 'stockLogs' => function($q) {
            $q->orderBy('date_time', 'desc')->orderBy('id', 'desc')->limit(50);
        }]);

        return view('fuel.trucks.show', compact('truck'));
    }

    public function update(Request $request, FuelTruck $truck)
    {
        $request->validate([
            'capacity' => 'required|numeric|min:1',
            'flowmeter_serial_number' => 'nullable|string|max:100',
            'dispenser_brand' => 'nullable|string|max:100',
            'site_id' => 'nullable|exists:sites,id',
        ]);

        $data = [
            'capacity' => $request->capacity,
            'flowmeter_serial_number' => $request->flowmeter_serial_number,
            'dispenser_brand' => $request->dispenser_brand,
            'site_id' => $request->site_id,
        ];

        if (auth()->user()->hasRole('Super Admin')) {
            if ($request->has('current_stock')) {
                $data['current_stock'] = (float) $request->current_stock;
            }
            if ($request->has('current_totalizer')) {
                $data['current_totalizer'] = (float) $request->current_totalizer;
            }
        }

        $truck->update($data);

        return redirect()->route('fuel.trucks.index')->with('success', 'Data Fuel Truck berhasil diperbarui.');
    }

    public function destroy(FuelTruck $truck)
    {
        $truck->delete();
        return redirect()->route('fuel.trucks.index')->with('success', 'Fuel Truck berhasil dihapus dari daftar dispenser.');
    }
}
