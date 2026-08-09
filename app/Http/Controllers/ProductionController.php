<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\ProductionFleet;
use App\Models\ProductionHauler;
use App\Models\ProductionSupport;
use App\Models\ProductionDelay;
use App\Models\MasterUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductionController extends Controller
{
    public function index()
    {
        $productions = Production::with(['fleets.digger', 'fleets.haulers', 'delays'])->latest()->paginate(10);
        return view('productions.index', compact('productions'));
    }

    public function create()
    {
        $units = MasterUnit::with('type')->get();
        
        $unitOptions = [];
        foreach ($units as $unit) {
            $typeName = $unit->type ? $unit->type->name : 'Unknown';
            if (!isset($unitOptions[$typeName])) {
                $unitOptions[$typeName] = [];
            }
            $unitOptions[$typeName][] = [
                'label' => $unit->nomor_unit,
                'value' => $unit->id
            ];
        }

        $formattedUnits = [];
        foreach ($unitOptions as $group => $options) {
            $formattedUnits[] = [
                'label' => $group,
                'options' => $options
            ];
        }

        return view('productions.create', compact('formattedUnits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'shift' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $production = Production::create([
                'date' => $request->date,
                'shift' => $request->shift,
                'notes' => $request->notes,
            ]);

            // Save Fleets & Haulers
            if ($request->has('fleets')) {
                foreach ($request->fleets as $fleetData) {
                    if (empty($fleetData['digger_id'])) continue;
                    
                    $fleet = ProductionFleet::create([
                        'production_id' => $production->id,
                        'digger_id' => $fleetData['digger_id'],
                        'material_type' => $fleetData['material_type'] ?? 'Unknown',
                        'distance' => $fleetData['distance'] ?? null,
                        'target_bcm_per_hour' => $fleetData['target_bcm_per_hour'] ?? null,
                    ]);

                    if (isset($fleetData['haulers']) && is_array($fleetData['haulers'])) {
                        foreach ($fleetData['haulers'] as $haulerData) {
                            if (empty($haulerData['hauler_id'])) continue;
                            
                            // Calculate total ritasi
                            $hourly = isset($haulerData['hourly_ritasi']) ? $haulerData['hourly_ritasi'] : [];
                            $totalRitasi = 0;
                            foreach ($hourly as $hour => $ritasi) {
                                $totalRitasi += (int)$ritasi;
                            }

                            ProductionHauler::create([
                                'production_fleet_id' => $fleet->id,
                                'hauler_id' => $haulerData['hauler_id'],
                                'payload' => $haulerData['payload'] ?? 0,
                                'hourly_ritasi' => $hourly,
                                'total_ritasi' => $totalRitasi,
                            ]);
                        }
                    }
                }
            }

            // Save Supports
            if ($request->has('supports')) {
                foreach ($request->supports as $supportData) {
                    if (empty($supportData['support_id'])) continue;

                    ProductionSupport::create([
                        'production_id' => $production->id,
                        'support_id' => $supportData['support_id'],
                        'hm_awal' => $supportData['hm_awal'] ?? null,
                        'hm_akhir' => $supportData['hm_akhir'] ?? null,
                    ]);
                }
            }

            // Save Delays
            if ($request->has('delays')) {
                foreach ($request->delays as $delayData) {
                    if (empty($delayData['start_time']) || empty($delayData['end_time'])) continue;

                    ProductionDelay::create([
                        'production_id' => $production->id,
                        'production_fleet_id' => (!empty($delayData['fleet_id'])) ? $delayData['fleet_id'] : null,
                        'start_time' => $delayData['start_time'],
                        'end_time' => $delayData['end_time'],
                        'delay_code' => $delayData['delay_code'] ?? 'Unknown',
                        'remarks' => $delayData['remarks'] ?? '',
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('productions.index')->with('success', 'Laporan Produksi berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show(Production $production)
    {
        $production->load([
            'fleets.digger', 
            'fleets.haulers.hauler', 
            'supports.support', 
            'delays'
        ]);
        return view('productions.show', compact('production'));
    }

    public function destroy(Production $production)
    {
        $production->delete();
        return redirect()->route('productions.index')->with('success', 'Laporan Produksi berhasil dihapus.');
    }

    public function edit(Production $production)
    {
        $units = MasterUnit::with('type')->get();
        
        $unitOptions = [];
        foreach ($units as $unit) {
            $typeName = $unit->type ? $unit->type->name : 'Unknown';
            if (!isset($unitOptions[$typeName])) {
                $unitOptions[$typeName] = [];
            }
            $unitOptions[$typeName][] = [
                'label' => $unit->nomor_unit,
                'value' => $unit->id
            ];
        }

        $formattedUnits = [];
        foreach ($unitOptions as $group => $options) {
            $formattedUnits[] = [
                'label' => $group,
                'options' => $options
            ];
        }

        $production->load([
            'fleets.haulers', 
            'supports', 
            'delays'
        ]);

        return view('productions.edit', compact('production', 'formattedUnits'));
    }

    public function update(Request $request, Production $production)
    {
        $request->validate([
            'date' => 'required|date',
            'shift' => 'required',
        ]);

        try {
            DB::beginTransaction();

            $production->update([
                'date' => $request->date,
                'shift' => $request->shift,
                'notes' => $request->notes,
            ]);

            // Clear old relationships safely
            $fleetIds = $production->fleets()->pluck('id');
            if ($fleetIds->count() > 0) {
                ProductionHauler::whereIn('production_fleet_id', $fleetIds)->delete();
                ProductionFleet::whereIn('id', $fleetIds)->delete();
            }
            ProductionSupport::where('production_id', $production->id)->delete();
            ProductionDelay::where('production_id', $production->id)->delete();

            // Save Fleets & Haulers
            if ($request->has('fleets')) {
                foreach ($request->fleets as $fleetData) {
                    if (empty($fleetData['digger_id'])) continue;
                    
                    $fleet = ProductionFleet::create([
                        'production_id' => $production->id,
                        'digger_id' => $fleetData['digger_id'],
                        'material_type' => $fleetData['material_type'] ?? 'Unknown',
                        'distance' => $fleetData['distance'] ?? null,
                        'target_bcm_per_hour' => $fleetData['target_bcm_per_hour'] ?? null,
                    ]);

                    if (isset($fleetData['haulers']) && is_array($fleetData['haulers'])) {
                        foreach ($fleetData['haulers'] as $haulerData) {
                            if (empty($haulerData['hauler_id'])) continue;
                            
                            // Calculate total ritasi
                            $hourly = isset($haulerData['hourly_ritasi']) ? $haulerData['hourly_ritasi'] : [];
                            $totalRitasi = 0;
                            foreach ($hourly as $hour => $ritasi) {
                                $totalRitasi += (int)$ritasi;
                            }

                            ProductionHauler::create([
                                'production_fleet_id' => $fleet->id,
                                'hauler_id' => $haulerData['hauler_id'],
                                'payload' => $haulerData['payload'] ?? 0,
                                'hourly_ritasi' => $hourly,
                                'total_ritasi' => $totalRitasi,
                            ]);
                        }
                    }
                }
            }

            // Save Supports
            if ($request->has('supports')) {
                foreach ($request->supports as $supportData) {
                    if (empty($supportData['support_id'])) continue;

                    ProductionSupport::create([
                        'production_id' => $production->id,
                        'support_id' => $supportData['support_id'],
                        'hm_awal' => $supportData['hm_awal'] ?? null,
                        'hm_akhir' => $supportData['hm_akhir'] ?? null,
                    ]);
                }
            }

            // Save Delays
            if ($request->has('delays')) {
                foreach ($request->delays as $delayData) {
                    if (empty($delayData['start_time']) || empty($delayData['end_time'])) continue;

                    ProductionDelay::create([
                        'production_id' => $production->id,
                        'production_fleet_id' => (!empty($delayData['fleet_id'])) ? $delayData['fleet_id'] : null,
                        'start_time' => $delayData['start_time'],
                        'end_time' => $delayData['end_time'],
                        'delay_code' => $delayData['delay_code'] ?? 'Unknown',
                        'remarks' => $delayData['remarks'] ?? '',
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('productions.index')->with('success', 'Laporan Produksi berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}
