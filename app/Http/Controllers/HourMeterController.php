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

    public function index(Request $request)
    {
        $query = \App\Models\HourMeter::with(['masterUnit.model', 'site']);

        if ($request->filled('start_date')) {
            $query->whereDate('date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('date', '<=', $request->end_date);
        }
        if ($request->filled('master_unit_id')) {
            $query->where('master_unit_id', $request->master_unit_id);
        }

        $sortBy = $request->get('sort_by', 'date');
        $sortOrder = $request->get('sort_order', 'desc');

        $allowedSorts = ['date', 'hm', 'created_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } elseif ($sortBy == 'unit') {
            // Sort by unit number via join if necessary, or just fallback to date
            $query->join('master_units', 'hour_meters.master_unit_id', '=', 'master_units.id')
                  ->orderBy('master_units.nomor_unit', $sortOrder)
                  ->select('hour_meters.*'); // ensure we only select HM columns
        } else {
            $query->orderBy('date', 'desc');
        }

        $hourMeters = $query->paginate(15)->withQueryString();
        $masterUnits = \App\Models\MasterUnit::orderBy('nomor_unit')->get();

        return view('hour-meters.index', compact('hourMeters', 'masterUnits'));
    }

    public function create()
    {
        $masterUnits = \App\Models\MasterUnit::with(['model', 'siteRelation'])->orderBy('nomor_unit')->get();
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
        $masterUnits = \App\Models\MasterUnit::with(['model', 'siteRelation'])->orderBy('nomor_unit')->get();
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
        \Log::info("Import Request received. File present: " . ($request->hasFile('file') ? 'Yes' : 'No'));

        if ($request->hasFile('file')) {
            \Log::info("File mime: " . $request->file('file')->getMimeType() . ", Extension: " . $request->file('file')->getClientOriginalExtension());
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'file' => 'required|mimes:xlsx,xls,csv|max:10240', // Max 10MB
        ]);

        if ($validator->fails()) {
            \Log::error("Import validation failed: ", $validator->errors()->toArray());
            return redirect()->route('hour-meters.index')->withErrors($validator);
        }

        try {
            $import = new \App\Imports\HourMeterImport;
            \Maatwebsite\Excel\Facades\Excel::import($import, $request->file('file'));
            
            $msg = "Import selesai. " . $import->createdCount . " data baru, " . $import->updatedCount . " data ditimpa.";
            if ($import->filledCount > 0) {
                $msg .= " " . $import->filledCount . " tanggal kosong berhasil diisi otomatis (auto-fill).";
            }
            if ($import->skippedCount > 0) {
                $msg .= " Terdapat " . $import->skippedCount . " baris yang dilewati (data tidak lengkap/unit tidak ditemukan).";
            }
            \Log::info("Import success: " . $msg);
            return redirect()->route('hour-meters.index')->with('success', $msg);
        } catch (\Exception $e) {
            \Log::error("Import exception: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->route('hour-meters.index')->with('error', 'Terjadi kesalahan saat import: ' . str_replace(["\r", "\n"], " ", $e->getMessage()));
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
