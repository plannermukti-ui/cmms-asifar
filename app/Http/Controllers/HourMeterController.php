<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HourMeterController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_hour_meters')->only(['index']);
        $this->middleware('permission:create_hour_meters')->only(['create', 'store']);
        $this->middleware('permission:edit_hour_meters')->only(['edit', 'update']);
        $this->middleware('permission:delete_hour_meters')->only(['destroy']);
    }

    public function index()
    {
        $hourMeters = \App\Models\HourMeter::with(['masterUnit.model', 'site'])->orderBy('date', 'desc')->paginate(15);
        return view('hour-meters.index', compact('hourMeters'));
    }

    public function create()
    {
        $masterUnits = \App\Models\MasterUnit::with(['model', 'site'])->orderBy('nomor_unit')->get();
        return view('hour-meters.create', compact('masterUnits'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'master_unit_id' => 'required|exists:master_units,id',
            'hm' => 'required|numeric|min:0',
        ]);

        $masterUnit = \App\Models\MasterUnit::findOrFail($request->master_unit_id);

        \App\Models\HourMeter::create([
            'date' => $request->date,
            'master_unit_id' => $masterUnit->id,
            'site_id' => $masterUnit->site_id,
            'hm' => $request->hm,
        ]);

        return redirect()->route('hour-meters.index')->with('success', 'Data Hour Meter berhasil ditambahkan.');
    }

    public function edit(\App\Models\HourMeter $hourMeter)
    {
        $masterUnits = \App\Models\MasterUnit::with(['model', 'site'])->orderBy('nomor_unit')->get();
        return view('hour-meters.edit', compact('hourMeter', 'masterUnits'));
    }

    public function update(Request $request, \App\Models\HourMeter $hourMeter)
    {
        $request->validate([
            'date' => 'required|date',
            'master_unit_id' => 'required|exists:master_units,id',
            'hm' => 'required|numeric|min:0',
        ]);

        $masterUnit = \App\Models\MasterUnit::findOrFail($request->master_unit_id);

        $hourMeter->update([
            'date' => $request->date,
            'master_unit_id' => $masterUnit->id,
            'site_id' => $masterUnit->site_id,
            'hm' => $request->hm,
        ]);

        return redirect()->route('hour-meters.index')->with('success', 'Data Hour Meter berhasil diperbarui.');
    }

    public function destroy(\App\Models\HourMeter $hourMeter)
    {
        $hourMeter->delete();
        return redirect()->route('hour-meters.index')->with('success', 'Data Hour Meter berhasil dihapus.');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240', // Max 10MB
        ]);

        try {
            \Maatwebsite\Excel\Facades\Excel::import(new \App\Imports\HourMeterImport, $request->file('file'));
            return redirect()->route('hour-meters.index')->with('success', 'Data Hour Meter berhasil diimport.');
        } catch (\Exception $e) {
            return redirect()->route('hour-meters.index')->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        // Generate a simple CSV or Excel template on the fly, or serve a static file.
        // We'll generate it using response()->streamDownload to create a simple CSV
        $headers = [
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Content-type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=Template_Import_HourMeter.csv',
            'Expires'             => '0',
            'Pragma'              => 'public'
        ];

        $columns = ['Date', 'Unit', 'HM'];
        $example = [date('Y-m-d'), 'EX-01', '12500.5'];

        $callback = function() use($columns, $example) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, $example);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
