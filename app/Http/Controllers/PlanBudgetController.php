<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PlanBudget;
use App\Models\PlanBudgetUnit;
use App\Models\MasterUnit;
use App\Models\Site;
use App\Models\WorkOrder;
use App\Models\WoSubtaskPart;
use App\Models\Part;
use App\Models\PlanBudgetPart;
use Carbon\Carbon;

class PlanBudgetController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_plan_budgets')->only(['index', 'show']);
        $this->middleware('permission:create_plan_budgets')->only(['create', 'store']);
        $this->middleware('permission:edit_plan_budgets')->only(['edit', 'update']);
        $this->middleware('permission:delete_plan_budgets')->only(['destroy']);
    }


    public function index(Request $request)
    {
        $query = PlanBudget::with('creator')->orderBy('period', 'desc');

        if ($request->has('site_id') && $request->site_id != '') {
            $query->where('site_id', $request->site_id);
        }

        $planBudgets = $query->paginate(15)->withQueryString();
        $sites = Site::all();
        $units = MasterUnit::where('active', true)->get();
        $parts = Part::all();

        return view('plan-budgets.index', compact('planBudgets', 'sites', 'units', 'parts'));
    }

    public function create()
    {
        return redirect()->route('plan-budgets.index', ['open_create' => 1]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'period' => 'required|date_format:Y-m',
            'site_id' => 'nullable|exists:sites,id',
            'units' => 'required|array',
            'units.*.master_unit_id' => 'required|exists:master_units,id',
            'units.*.target_pa' => 'required|numeric|min:0|max:100',
            'units.*.parts' => 'nullable|array',
            'units.*.parts.*.part_id' => 'required_with:units.*.parts|exists:parts,id',
            'units.*.parts.*.qty' => 'required_with:units.*.parts|numeric|min:1',
        ]);

        // Check if plan for this period and site already exists
        $exists = PlanBudget::where('period', $request->period)
                            ->where('site_id', $request->site_id)
                            ->exists();
        if ($exists) {
            return back()->with('error', 'Plan Budget untuk periode dan site tersebut sudah ada.')->withInput();
        }

        $planBudget = PlanBudget::create([
            'site_id' => $request->site_id,
            'period' => $request->period,
            'status' => 'Draft',
            'created_by' => auth()->id()
        ]);

        foreach ($request->units as $u) {
            $pbu = PlanBudgetUnit::create([
                'plan_budget_id' => $planBudget->id,
                'master_unit_id' => $u['master_unit_id'],
                'target_pa' => $u['target_pa'],
                'planned_cost' => 0,
            ]);
            
            $plannedCost = 0;
            if (isset($u['parts']) && is_array($u['parts'])) {
                foreach ($u['parts'] as $p) {
                    $partModel = Part::find($p['part_id']);
                    $cost = $partModel->cost ?? 0;
                    $totalPrice = $cost * $p['qty'];
                    $plannedCost += $totalPrice;
                    
                    PlanBudgetPart::create([
                        'plan_budget_unit_id' => $pbu->id,
                        'part_id' => $p['part_id'],
                        'qty' => $p['qty'],
                        'price' => $cost,
                        'total_price' => $totalPrice
                    ]);
                }
            }
            
            $pbu->update(['planned_cost' => $plannedCost]);
        }

        return redirect()->route('plan-budgets.index')->with('success', 'Plan Budget berhasil dibuat.');
    }

    public function edit(PlanBudget $planBudget)
    {
        if ($planBudget->status == 'Approved') {
            return redirect()->route('plan-budgets.index')->with('error', 'Plan Budget yang sudah Approved tidak dapat diedit.');
        }

        $planBudget->load('units.unit', 'units.parts.part');
        $sites = Site::all();
        $units = MasterUnit::where('active', true)->get();
        $parts = Part::all();

        return view('plan-budgets.edit', compact('planBudget', 'sites', 'units', 'parts'));
    }

    public function update(Request $request, PlanBudget $planBudget)
    {
        if ($planBudget->status == 'Approved') {
            return redirect()->route('plan-budgets.index')->with('error', 'Plan Budget yang sudah Approved tidak dapat diedit.');
        }

        $request->validate([
            'status' => 'required|in:Draft,Approved',
            'units' => 'required|array',
            'units.*.master_unit_id' => 'required|exists:master_units,id',
            'units.*.target_pa' => 'required|numeric|min:0|max:100',
            'units.*.parts' => 'nullable|array',
            'units.*.parts.*.part_id' => 'required_with:units.*.parts|exists:parts,id',
            'units.*.parts.*.qty' => 'required_with:units.*.parts|numeric|min:1',
        ]);

        $planBudget->update([
            'status' => $request->status,
        ]);

        // Re-sync units
        $planBudget->units()->delete();
        
        foreach ($request->units as $u) {
            $pbu = PlanBudgetUnit::create([
                'plan_budget_id' => $planBudget->id,
                'master_unit_id' => $u['master_unit_id'],
                'target_pa' => $u['target_pa'],
                'planned_cost' => 0,
            ]);
            
            $plannedCost = 0;
            if (isset($u['parts']) && is_array($u['parts'])) {
                foreach ($u['parts'] as $p) {
                    $partModel = Part::find($p['part_id']);
                    $cost = $partModel->cost ?? 0;
                    $totalPrice = $cost * $p['qty'];
                    $plannedCost += $totalPrice;
                    
                    PlanBudgetPart::create([
                        'plan_budget_unit_id' => $pbu->id,
                        'part_id' => $p['part_id'],
                        'qty' => $p['qty'],
                        'price' => $cost,
                        'total_price' => $totalPrice
                    ]);
                }
            }
            
            $pbu->update(['planned_cost' => $plannedCost]);
        }

        return redirect()->route('plan-budgets.index')->with('success', 'Plan Budget berhasil diperbarui.');
    }

    public function show(PlanBudget $planBudget)
    {
        $planBudget->load('units.unit.type', 'units.unit.model');
        
        $startDate = Carbon::createFromFormat('Y-m', $planBudget->period)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();
        
        // EWH for a full month
        $daysInMonth = $startDate->daysInMonth;
        $ewh = $daysInMonth * 24;

        foreach ($planBudget->units as $bu) {
            $unit = $bu->unit;
            
            // 1. Calculate Actual PA
            $wos = WorkOrder::where('master_unit_id', $unit->id)
                ->where('opportunity', false)
                ->where(function($q) use ($startDate, $endDate) {
                    $q->whereBetween('waktu_bd', [$startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s')])
                      ->orWhereBetween('waktu_rfu', [$startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s')])
                      ->orWhere(function($q2) use ($startDate, $endDate) {
                          $q2->where('waktu_bd', '<', $startDate->format('Y-m-d H:i:s'))
                             ->where(function($q3) use ($endDate) {
                                 $q3->whereNull('waktu_rfu')
                                    ->orWhere('waktu_rfu', '>', $endDate->format('Y-m-d H:i:s'));
                             });
                      });
                })
                ->get();

            $bdHrsTotal = 0;
            $jamSekarang = Carbon::parse(Carbon::now('Asia/Makassar')->format('Y-m-d H:i:s'));

            foreach ($wos as $wo) {
                if (strtolower($wo->tipe_wo) == 'plan' && strtolower($wo->status_wo) != 'completed') {
                    continue;
                }
                
                $bdStart = $wo->waktu_bd ? Carbon::parse($wo->waktu_bd) : null;
                if ($wo->waktu_rfu) {
                    $bdEnd = Carbon::parse($wo->waktu_rfu);
                } else {
                    $bdEnd = $jamSekarang;
                }
                
                if (!$bdStart) continue;

                $overlapStart = max($bdStart->timestamp, $startDate->timestamp);
                $overlapEnd = min($bdEnd->timestamp, $endDate->timestamp);
                
                if ($overlapEnd > $overlapStart) {
                    $bdHrsTotal += ($overlapEnd - $overlapStart) / 3600;
                }
            }

            $actualPa = $ewh > 0 ? (($ewh - $bdHrsTotal) / $ewh) * 100 : 0;
            $bu->actual_pa = $actualPa;

            // 2. Calculate Actual Cost
            // Gunakan tanggal pelaksanaan WO (BD/RFU), bukan created_at. WO dapat dibuat
            // setelah pekerjaan berlangsung, sehingga created_at akan salah periode budget.
            $monthlyWos = WorkOrder::where('master_unit_id', $unit->id)
                ->where(function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('waktu_bd', [$startDate, $endDate])
                        ->orWhereBetween('waktu_rfu', [$startDate, $endDate])
                        ->orWhere(function ($q2) use ($startDate, $endDate) {
                            $q2->where('waktu_bd', '<', $startDate)
                                ->where(function ($q3) use ($endDate) {
                                    $q3->whereNull('waktu_rfu')
                                        ->orWhere('waktu_rfu', '>', $endDate);
                                });
                        });
                })
                ->pluck('id');
            
            $actualCost = WoSubtaskPart::whereHas('subtask.task', function($q) use ($monthlyWos) {
                    $q->whereIn('work_order_id', $monthlyWos);
                })
                ->with('part')
                ->get()
                ->sum(function ($item) {
                    return $item->qty * ($item->part->cost ?? 0);
                });
                
            $bu->actual_cost = $actualCost;
        }

        return view('plan-budgets.show', compact('planBudget'));
    }
    
    public function destroy(PlanBudget $planBudget)
    {
        if ($planBudget->status == 'Approved') {
            return back()->with('error', 'Plan Budget yang sudah Approved tidak dapat dihapus.');
        }
        
        $planBudget->delete();
        return back()->with('success', 'Plan Budget berhasil dihapus.');
    }
}
