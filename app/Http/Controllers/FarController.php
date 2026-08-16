<?php

namespace App\Http\Controllers;

use App\Models\Far;
use App\Models\FarAttachment;
use App\Models\MasterUnit;
use App\Models\Site;
use App\Models\WorkOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class FarController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_fars')->only(['index', 'show']);
        $this->middleware('permission:create_fars')->only(['create', 'store']);
        $this->middleware('permission:edit_fars')->only(['edit', 'update']);
        $this->middleware('permission:delete_fars')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $site_id = Auth::user()->site_id ?? null;
        
        $query = Far::with(['masterUnit', 'siteRelation', 'reporter', 'workOrder'])
                    ->when($site_id, function ($q) use ($site_id) {
                        return $q->where('site_id', $site_id);
                    });
                    
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('no_far', 'like', "%{$search}%")
                  ->orWhereHas('masterUnit', function($qu) use ($search) {
                      $qu->where('nomor_unit', 'like', "%{$search}%");
                  });
            });
        }
        
        $fars = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('fars.index', compact('fars'));
    }

    public function create()
    {
        $site_id = Auth::user()->site_id ?? null;
        
        $sites = Site::all();
        $parts = \App\Models\Part::orderBy('part_number')->get();
        
        $units = MasterUnit::when($site_id, function ($query) use ($site_id) {
                                return $query->where('site_id', $site_id);
                            })->get();
                            
        $workOrders = WorkOrder::when($site_id, function ($query) use ($site_id) {
                                    return $query->where('site_id', $site_id);
                                })->orderBy('created_at', 'desc')->get();
                                
        // Auto-generate FAR Number: FAR-{YYMM}-{0001}
        $prefix = 'FAR-' . date('ym') . '-';
        $lastFar = Far::where('no_far', 'like', $prefix . '%')->orderBy('id', 'desc')->first();
        if ($lastFar) {
            $lastNumber = (int) substr($lastFar->no_far, -4);
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            $newNumber = '0001';
        }
        $no_far = $prefix . $newNumber;

        return view('fars.create', compact('sites', 'units', 'workOrders', 'no_far', 'parts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_far' => 'required|unique:fars,no_far',
            'site_id' => 'required',
            'master_unit_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->except(['attachments']);
            $data['reported_by'] = Auth::id();
            
            $far = Far::create($data);

            if ($request->has('attachments') && is_array($request->attachments)) {
                foreach ($request->attachments as $index => $attachment) {
                    if (isset($attachment['photo']) && $attachment['photo']->isValid()) {
                        $path = $attachment['photo']->store('far_attachments', 'public');
                        
                        FarAttachment::create([
                            'far_id' => $far->id,
                            'component' => $attachment['component'] ?? null,
                            'observation' => $attachment['observation'] ?? null,
                            'photo_path' => $path,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('fars.show', $far)->with('success', 'Failure Analysis Report berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Far $far)
    {
        $far->load(['masterUnit.model', 'masterUnit.type', 'siteRelation', 'reporter', 'workOrder', 'attachments', 'signatures.user']);
        
        $signCreator = $far->signatures->where('role_name', 'Pembuat')->first();
        $signSupervisor = $far->signatures->where('role_name', 'Supervisor')->first();
        $signSuperintendent = $far->signatures->where('role_name', 'Superintendent')->first();

        return view('fars.show', compact('far', 'signCreator', 'signSupervisor', 'signSuperintendent'));
    }

    public function edit(Far $far)
    {
        $site_id = Auth::user()->site_id ?? null;
        
        $sites = Site::all();
        $units = MasterUnit::when($site_id, function ($query) use ($site_id) {
                                return $query->where('site_id', $site_id);
                            })->get();
        $workOrders = WorkOrder::when($site_id, function ($query) use ($site_id) {
                                    return $query->where('site_id', $site_id);
                                })->orderBy('created_at', 'desc')->get();
        
        $far->load('attachments');
        $parts = \App\Models\Part::orderBy('part_number')->get();

        return view('fars.edit', compact('far', 'sites', 'units', 'workOrders', 'parts'));
    }

    public function update(Request $request, Far $far)
    {
        $request->validate([
            'no_far' => 'required|unique:fars,no_far,' . $far->id,
            'site_id' => 'required',
            'master_unit_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->except(['attachments', 'delete_attachments']);
            $far->update($data);

            // Handle deleted attachments
            if ($request->has('delete_attachments') && is_array($request->delete_attachments)) {
                foreach ($request->delete_attachments as $attach_id) {
                    $attachment = FarAttachment::find($attach_id);
                    if ($attachment && $attachment->far_id == $far->id) {
                        if (Storage::disk('public')->exists($attachment->photo_path)) {
                            Storage::disk('public')->delete($attachment->photo_path);
                        }
                        $attachment->delete();
                    }
                }
            }

            // Handle new attachments
            if ($request->has('attachments') && is_array($request->attachments)) {
                foreach ($request->attachments as $index => $attachment) {
                    if (isset($attachment['photo']) && $attachment['photo']->isValid()) {
                        $path = $attachment['photo']->store('far_attachments', 'public');
                        
                        FarAttachment::create([
                            'far_id' => $far->id,
                            'component' => $attachment['component'] ?? null,
                            'observation' => $attachment['observation'] ?? null,
                            'photo_path' => $path,
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('fars.show', $far)->with('success', 'Failure Analysis Report berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Far $far)
    {
        try {
            foreach ($far->attachments as $attachment) {
                if (Storage::disk('public')->exists($attachment->photo_path)) {
                    Storage::disk('public')->delete($attachment->photo_path);
                }
            }
            $far->delete();
            return redirect()->route('fars.index')->with('success', 'FAR berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus FAR.');
        }
    }
}
