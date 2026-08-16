<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_vendors')->only(['index', 'show']);
        $this->middleware('permission:create_vendors')->only(['create', 'store']);
        $this->middleware('permission:edit_vendors')->only(['edit', 'update']);
        $this->middleware('permission:delete_vendors')->only(['destroy']);
    }

    public function index(Request $request)
    {
        $query = Vendor::query();
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('contact_person', 'like', '%' . $request->search . '%');
        }

        $vendors = $query->latest()->paginate(15);
        return view('vendors.index', compact('vendors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        Vendor::create($validated);
        return redirect()->route('vendors.index')->with('success', 'Vendor berhasil ditambahkan');
    }

    public function update(Request $request, Vendor $vendor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $vendor->update($validated);
        return redirect()->route('vendors.index')->with('success', 'Data Vendor berhasil diperbarui');
    }

    public function destroy(Vendor $vendor)
    {
        if ($vendor->jwos()->count() > 0) {
            return redirect()->route('vendors.index')->with('error', 'Vendor tidak bisa dihapus karena memiliki riwayat JWO');
        }
        $vendor->delete();
        return redirect()->route('vendors.index')->with('success', 'Vendor berhasil dihapus');
    }
}
