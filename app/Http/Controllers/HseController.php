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
        ]);

        HseLoto::create([
            'work_order_id' => $workOrder->id,
            'site_id' => $workOrder->site_id,
            'isolation_point' => $request->isolation_point,
            'lock_number' => $request->lock_number,
            'tag_number' => $request->tag_number,
            'applied_by' => Auth::id(),
            'applied_at' => now(),
            'status' => 'Active',
        ]);

        return back()->with('success', 'LOTO berhasil dipasang.');
    }

    public function removeLoto(HseLoto $loto)
    {
        $loto->update([
            'status' => 'Removed',
            'removed_by' => Auth::id(),
            'removed_at' => now(),
        ]);

        return back()->with('success', 'LOTO berhasil dilepas.');
    }
}
