<?php

namespace App\Http\Controllers;

use App\Models\IncidentReport;
use Illuminate\Http\Request;

class IncidentReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_incident_reports')->only(['index', 'show']);
        $this->middleware('permission:create_incident_reports')->only(['create', 'store']);
        $this->middleware('permission:edit_incident_reports')->only(['edit', 'update', 'uploadDocument']);
        $this->middleware('permission:delete_incident_reports')->only(['destroy']);
    }

    public function index()
    {
        $reports = IncidentReport::with(['transaction.tool', 'mechanic', 'approver'])->orderBy('created_at', 'desc')->paginate(10);
        return view('incident_reports.index', compact('reports'));
    }

    public function edit(IncidentReport $incidentReport)
    {
        return view('incident_reports.edit', compact('incidentReport'));
    }

    public function update(Request $request, IncidentReport $incidentReport)
    {
        $request->validate([
            'status_approval' => 'required|in:Pending,Approved,Rejected',
        ]);

        $data = ['status_approval' => $request->status_approval];
        
        if ($request->status_approval === 'Approved' || $request->status_approval === 'Rejected') {
            $data['approved_by'] = auth()->id();
        } else {
            $data['approved_by'] = null;
        }

        $incidentReport->update($data);

        return redirect()->route('incident-reports.index')->with('success', 'Status Berita Acara berhasil diupdate.');
    }

    public function show(IncidentReport $incidentReport)
    {
        // For printing PDF/Viewing details
        return view('incident_reports.show', compact('incidentReport'));
    }

    public function destroy(IncidentReport $incidentReport)
    {
        $incidentReport->delete();
        return redirect()->route('incident-reports.index')->with('success', 'Berita Acara berhasil dihapus.');
    }

    public function uploadDocument(Request $request, IncidentReport $incidentReport)
    {
        $request->validate([
            'signed_document' => 'required|mimes:pdf|max:5120', // Max 5MB PDF
        ]);

        if ($request->hasFile('signed_document')) {
            $file = $request->file('signed_document');
            $filename = 'IR_' . $incidentReport->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/incident_reports', $filename);
            
            $incidentReport->update([
                'signed_document' => $filename
            ]);

            return redirect()->back()->with('success', 'Dokumen hasil scan berhasil diupload.');
        }

        return redirect()->back()->with('error', 'Gagal mengupload dokumen.');
    }
}
