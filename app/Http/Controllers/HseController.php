<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\HseJsa;
use App\Models\HseJsaStep;
use App\Models\HsePtw;
use App\Models\HseLoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HseController extends Controller
{
    public function __construct()
    {
        // JSA Permissions
        $this->middleware('permission:create_hse_jsas')->only(['storeJsa']);
        $this->middleware('permission:edit_hse_jsas')->only(['editJsa', 'updateJsa']);
        $this->middleware('permission:delete_hse_jsas')->only(['destroyJsa']);

        // PTW Permissions
        $this->middleware('permission:create_hse_ptws')->only(['storePtw']);
        $this->middleware('permission:edit_hse_ptws')->only(['editPtw', 'updatePtw']);
        $this->middleware('permission:delete_hse_ptws')->only(['destroyPtw']);

        // LOTO Permissions
        $this->middleware('permission:create_hse_lotos')->only(['storeLoto']);
        $this->middleware('permission:edit_hse_lotos')->only(['editLoto', 'updateLoto']);
        $this->middleware('permission:delete_hse_lotos')->only(['removeLoto']);
    }

    // ==========================================
    // JSA (Job Safety Analysis)
    // ==========================================
    
    public function printJsaTemplate(WorkOrder $workOrder)
    {
        return view('work_orders.jsa-template', compact('workOrder'));
    }
    public function storeJsa(Request $request, WorkOrder $workOrder)
    {
        $request->validate([
            'notes' => 'nullable|string',
            'document_scan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'steps' => 'required_without:document_scan|array',
            'steps.*.job_step' => 'required_with:steps|string',
            'steps.*.potential_hazard' => 'required_with:steps|string',
            'steps.*.control_measure' => 'required_with:steps|string',
        ]);

        DB::beginTransaction();
        try {
            $documentPath = null;
            if ($request->hasFile('document_scan')) {
                $documentPath = $request->file('document_scan')->store('jsa_scans', 'public');
            }

            // Check if admin creates it, it can be immediately Active/Approved
            // For now, let's just make it Approved automatically to keep it flexible as requested
            $status = Auth::user()->can('approve_hse') ? 'Approved' : 'Approved'; // Just making it flexible

            $jsa = HseJsa::create([
                'work_order_id' => $workOrder->id,
                'site_id' => $workOrder->site_id,
                'created_by' => Auth::id(),
                'status' => $status,
                'approved_by' => $status == 'Approved' ? Auth::id() : null,
                'approved_at' => $status == 'Approved' ? now() : null,
                'notes' => $request->notes,
                'document_scan' => $documentPath,
            ]);

            if ($request->has('steps') && is_array($request->steps)) {
                foreach ($request->steps as $step) {
                    if (!empty($step['job_step'])) {
                        HseJsaStep::create([
                            'hse_jsa_id' => $jsa->id,
                            'job_step' => $step['job_step'],
                            'potential_hazard' => $step['potential_hazard'],
                            'control_measure' => $step['control_measure'],
                        ]);
                    }
                }
            }

            DB::commit();
            return back()->with('success', 'JSA berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function editJsa(HseJsa $jsa)
    {
        $workOrder = $jsa->workOrder;
        return response()->json([
            'id' => $jsa->id,
            'notes' => $jsa->notes,
            'status' => $jsa->status,
            'document_scan' => $jsa->document_scan ? \Storage::disk('public')->url($jsa->document_scan) : null,
            'steps' => $jsa->steps->map(fn($step) => [
                'id' => $step->id,
                'job_step' => $step->job_step,
                'potential_hazard' => $step->potential_hazard,
                'control_measure' => $step->control_measure,
            ]),
        ]);
    }

    public function updateJsa(Request $request, HseJsa $jsa)
    {
        $request->validate([
            'notes' => 'nullable|string',
            'document_scan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'steps' => 'required_without:document_scan|array',
            'steps.*.job_step' => 'required_with:steps|string',
            'steps.*.potential_hazard' => 'required_with:steps|string',
            'steps.*.control_measure' => 'required_with:steps|string',
        ]);

        DB::beginTransaction();
        try {
            $documentPath = $jsa->document_scan;
            if ($request->hasFile('document_scan')) {
                // Delete old file if exists
                if ($jsa->document_scan && \Storage::disk('public')->exists($jsa->document_scan)) {
                    \Storage::disk('public')->delete($jsa->document_scan);
                }
                $documentPath = $request->file('document_scan')->store('jsa_scans', 'public');
            }

            $jsa->update([
                'notes' => $request->notes,
                'document_scan' => $documentPath,
            ]);

            // Delete old steps and recreate
            $jsa->steps()->delete();
            if ($request->has('steps') && is_array($request->steps)) {
                foreach ($request->steps as $step) {
                    if (!empty($step['job_step'])) {
                        HseJsaStep::create([
                            'hse_jsa_id' => $jsa->id,
                            'job_step' => $step['job_step'],
                            'potential_hazard' => $step['potential_hazard'],
                            'control_measure' => $step['control_measure'],
                        ]);
                    }
                }
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'JSA berhasil diperbarui.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    public function approveJsa(HseJsa $jsa)
    {
        $jsa->update([
            'status' => 'Approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);
        return back()->with('success', 'JSA berhasil disetujui.');
    }

    public function destroyJsa(HseJsa $jsa)
    {
        // Delete associated file if exists
        if ($jsa->document_scan && \Storage::disk('public')->exists($jsa->document_scan)) {
            \Storage::disk('public')->delete($jsa->document_scan);
        }
        $jsa->delete();
        return back()->with('success', 'JSA berhasil dihapus.');
    }

    // ==========================================
    // PTW (Permit to Work)
    // ==========================================

    public function printPtwTemplate(Request $request, WorkOrder $workOrder)
    {
        $permitType = $request->query('type', 'Tipe Permit: ________________');
        return view('work_orders.ptw-template', compact('workOrder', 'permitType'));
    }
    public function storePtw(Request $request, WorkOrder $workOrder)
    {
        $request->validate([
            'permit_type' => 'required|string',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'notes' => 'nullable|string',
            'document_scan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $status = 'Approved'; // Keep flexible as requested

        $documentPath = null;
        if ($request->hasFile('document_scan')) {
            $documentPath = $request->file('document_scan')->store('ptw_scans', 'public');
        }

        HsePtw::create([
            'work_order_id' => $workOrder->id,
            'site_id' => $workOrder->site_id,
            'permit_type' => $request->permit_type,
            'valid_from' => $request->valid_from,
            'valid_to' => $request->valid_to,
            'applicant_id' => Auth::id(),
            'status' => $status,
            'approver_id' => $status == 'Approved' ? Auth::id() : null,
            'notes' => $request->notes,
            'document_scan' => $documentPath,
        ]);

        return back()->with('success', 'Permit to Work berhasil dibuat.');
    }

    public function editPtw(HsePtw $ptw)
    {
        return response()->json([
            'id' => $ptw->id,
            'permit_type' => $ptw->permit_type,
            'valid_from' => $ptw->valid_from->format('Y-m-d\TH:i'),
            'valid_to' => $ptw->valid_to->format('Y-m-d\TH:i'),
            'notes' => $ptw->notes,
            'status' => $ptw->status,
            'document_scan' => $ptw->document_scan ? \Storage::disk('public')->url($ptw->document_scan) : null,
        ]);
    }

    public function updatePtw(Request $request, HsePtw $ptw)
    {
        $request->validate([
            'permit_type' => 'required|string',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after_or_equal:valid_from',
            'notes' => 'nullable|string',
            'document_scan' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $documentPath = $ptw->document_scan;
        if ($request->hasFile('document_scan')) {
            // Delete old file if exists
            if ($ptw->document_scan && \Storage::disk('public')->exists($ptw->document_scan)) {
                \Storage::disk('public')->delete($ptw->document_scan);
            }
            $documentPath = $request->file('document_scan')->store('ptw_scans', 'public');
        }

        $ptw->update([
            'permit_type' => $request->permit_type,
            'valid_from' => $request->valid_from,
            'valid_to' => $request->valid_to,
            'notes' => $request->notes,
            'document_scan' => $documentPath,
        ]);

        return response()->json(['success' => true, 'message' => 'Permit to Work berhasil diperbarui.']);
    }

    public function approvePtw(HsePtw $ptw)
    {
        $ptw->update([
            'status' => 'Approved',
            'approver_id' => Auth::id(),
        ]);
        return back()->with('success', 'Permit to Work berhasil disetujui.');
    }

    public function destroyPtw(HsePtw $ptw)
    {
        // Delete associated file if exists
        if ($ptw->document_scan && \Storage::disk('public')->exists($ptw->document_scan)) {
            \Storage::disk('public')->delete($ptw->document_scan);
        }
        $ptw->delete();
        return back()->with('success', 'Permit to Work berhasil dihapus.');
    }

    // ==========================================
    // LOTO (Lockout/Tagout)
    // ==========================================
    public function storeLoto(Request $request, WorkOrder $workOrder)
    {
        $request->validate([
            'isolation_point' => 'required|string',
            'lock_number' => 'nullable|string',
            'tag_number' => 'nullable|string',
            'applied_mechanic_id' => 'nullable|exists:mechanics,id',
            'applied_at' => 'nullable|date',
        ]);

        HseLoto::create([
            'work_order_id' => $workOrder->id,
            'site_id' => $workOrder->site_id,
            'isolation_point' => $request->isolation_point,
            'lock_number' => $request->lock_number,
            'tag_number' => $request->tag_number,
            'applied_mechanic_id' => $request->applied_mechanic_id,
            'applied_by' => Auth::id(),
            'applied_at' => $request->applied_at ? \Carbon\Carbon::parse($request->applied_at) : now(),
            'status' => 'Active',
        ]);

        return back()->with('success', 'LOTO berhasil dipasang.');
    }

    public function editLoto(HseLoto $loto)
    {
        return response()->json([
            'id' => $loto->id,
            'isolation_point' => $loto->isolation_point,
            'lock_number' => $loto->lock_number,
            'tag_number' => $loto->tag_number,
            'applied_mechanic_id' => $loto->applied_mechanic_id,
            'applied_at' => $loto->applied_at ? $loto->applied_at->format('Y-m-d\TH:i') : '',
            'removed_mechanic_id' => $loto->removed_mechanic_id,
            'removed_at' => $loto->removed_at ? $loto->removed_at->format('Y-m-d\TH:i') : '',
            'status' => $loto->status,
        ]);
    }

    public function updateLoto(Request $request, HseLoto $loto)
    {
        $request->validate([
            'isolation_point' => 'required|string',
            'lock_number' => 'nullable|string',
            'tag_number' => 'nullable|string',
            'applied_mechanic_id' => 'nullable|exists:mechanics,id',
            'applied_at' => 'nullable|date',
            'removed_mechanic_id' => 'nullable|exists:mechanics,id',
            'removed_at' => 'nullable|date',
            'status' => 'nullable|in:Active,Removed',
        ]);

        $updateData = [
            'isolation_point' => $request->isolation_point,
            'lock_number' => $request->lock_number,
            'tag_number' => $request->tag_number,
            'applied_mechanic_id' => $request->applied_mechanic_id,
            'applied_at' => $request->applied_at ? \Carbon\Carbon::parse($request->applied_at) : $loto->applied_at,
        ];

        if ($request->filled('removed_mechanic_id')) {
            $updateData['removed_mechanic_id'] = $request->removed_mechanic_id;
        }
        if ($request->filled('removed_at')) {
            $updateData['removed_at'] = \Carbon\Carbon::parse($request->removed_at);
        }
        if ($request->filled('status')) {
            $updateData['status'] = $request->status;
        }

        $loto->update($updateData);

        return response()->json(['success' => true, 'message' => 'LOTO berhasil diperbarui.']);
    }

    public function removeLoto(Request $request, HseLoto $loto)
    {
        $request->validate([
            'removed_mechanic_id' => 'nullable|exists:mechanics,id',
            'removed_at' => 'nullable|date',
        ]);

        $loto->update([
            'status' => 'Removed',
            'removed_mechanic_id' => $request->removed_mechanic_id,
            'removed_by' => Auth::id(),
            'removed_at' => $request->removed_at ? \Carbon\Carbon::parse($request->removed_at) : now(),
        ]);

        return back()->with('success', 'LOTO berhasil dilepas.');
    }
}
