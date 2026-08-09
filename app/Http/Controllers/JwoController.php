<?php

namespace App\Http\Controllers;

use App\Models\Jwo;
use App\Models\Vendor;
use App\Models\MasterUnit;
use App\Models\ComponentGroup;
use App\Models\Part;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Carbon\Carbon;

class JwoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Jwo::with(['vendor', 'unit', 'part', 'workOrder']);
        
        if ($request->filled('search')) {
            $query->where('no_jwo', 'like', '%' . $request->search . '%')
                  ->orWhereHas('vendor', function($q) use($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }
        
        if ($request->filled('status')) {
            $status = (array) $request->status;
            if (count($status) === 1 && strpos($status[0], ',') !== false) $status = explode(',', $status[0]);
            $status = array_filter($status);
            if (!empty($status)) $query->whereIn('status', $status);
        }

        $jwos = $query->latest()->paginate(15);
        
        // Data for create/edit modal
        $vendors = Vendor::orderBy('name')->get();
        $units = MasterUnit::orderBy('nomor_unit')->get();
        $parts = Part::orderBy('part_number')->get();
        $componentGroups = ComponentGroup::orderBy('name')->get();
        $workOrders = WorkOrder::orderBy('id', 'desc')->get();

        return view('jwo.index', compact('jwos', 'vendors', 'units', 'parts', 'componentGroups', 'workOrders'));
    }

    public function create(Request $request)
    {
        $vendors = Vendor::orderBy('name')->get();
        $units = MasterUnit::orderBy('nomor_unit')->get();
        $parts = Part::orderBy('part_number')->get();
        $componentGroups = ComponentGroup::orderBy('name')->get();
        
        $wo = null;
        if ($request->has('work_order_id')) {
            $wo = WorkOrder::with('unit')->find($request->work_order_id);
        }
        
        return view('jwo.create', compact('vendors', 'units', 'parts', 'componentGroups', 'wo'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'work_order_id' => 'nullable|exists:work_orders,id',
            'unit_id' => 'nullable|exists:master_units,id',
            'component_group_id' => 'nullable|exists:component_groups,id',
            'part_id' => 'required|exists:parts,id',
            'problem_description' => 'required|string',
            'request_action' => 'required|string',
            'date_expected' => 'nullable|date',
            'remarks' => 'nullable|string',
            'photo_1' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'photo_2' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        $validated['no_jwo'] = Jwo::generateNoJwo();
        $validated['status'] = 'Progress Site';
        $validated['created_by'] = auth()->id();

        // Handle photo uploads
        if ($request->hasFile('photo_1')) {
            $path = $request->file('photo_1')->store('jwo_photos', 'public');
            $validated['photo_1'] = basename($path);
        }
        if ($request->hasFile('photo_2')) {
            $path = $request->file('photo_2')->store('jwo_photos', 'public');
            $validated['photo_2'] = basename($path);
        }

        $jwo = Jwo::create($validated);
        return redirect()->route('jwos.index')->with('success', 'JWO berhasil dibuat');
    }

    public function show(Jwo $jwo)
    {
        $jwo->load(['vendor', 'unit', 'part', 'workOrder', 'componentGroup', 'creator']);
        return view('jwo.show', compact('jwo'));
    }

    public function edit(Jwo $jwo)
    {
        $vendors = Vendor::orderBy('name')->get();
        $units = MasterUnit::orderBy('nomor_unit')->get();
        $parts = Part::orderBy('part_number')->get();
        $componentGroups = ComponentGroup::orderBy('name')->get();
        
        return view('jwo.edit', compact('jwo', 'vendors', 'units', 'parts', 'componentGroups'));
    }

    public function update(Request $request, Jwo $jwo)
    {
        $validated = $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'work_order_id' => 'nullable|exists:work_orders,id',
            'unit_id' => 'nullable|exists:master_units,id',
            'component_group_id' => 'nullable|exists:component_groups,id',
            'part_id' => 'required|exists:parts,id',
            'problem_description' => 'required|string',
            'request_action' => 'required|string',
            'date_expected' => 'nullable|date',
            'remarks' => 'nullable|string',
            'photo_1' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'photo_2' => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
        ]);

        if ($request->hasFile('photo_1')) {
            $path = $request->file('photo_1')->store('jwo_photos', 'public');
            $validated['photo_1'] = basename($path);
        }
        if ($request->hasFile('photo_2')) {
            $path = $request->file('photo_2')->store('jwo_photos', 'public');
            $validated['photo_2'] = basename($path);
        }

        $jwo->update($validated);
        return redirect()->route('jwos.index')->with('success', 'JWO berhasil diperbarui');
    }
    
    public function updateStatus(Request $request, Jwo $jwo)
    {
        $request->validate([
            'status' => 'required|in:Progress Site,Sent,Progress Vendor,Completed,Cancelled',
            'cost' => 'nullable|numeric',
            'date_sent' => 'nullable|date',
        ]);
        
        $data = ['status' => $request->status];
        
        if ($request->has('date_sent')) {
            $data['date_sent'] = $request->date_sent;
        } elseif ($request->status == 'Sent' && !$jwo->date_sent) {
            $data['date_sent'] = Carbon::now();
        } 
        
        if ($request->status == 'Completed' && !$jwo->date_returned) {
            $data['date_returned'] = Carbon::now();
        }
        
        if ($request->has('cost')) {
            $data['cost'] = $request->cost;
        }

        $jwo->update($data);
        return redirect()->back()->with('success', 'Status JWO diperbarui');
    }

    public function destroy(Jwo $jwo)
    {
        $jwo->delete();
        return redirect()->route('jwos.index')->with('success', 'JWO berhasil dihapus');
    }
}
