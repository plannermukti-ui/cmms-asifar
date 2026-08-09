<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentSignature;
use Illuminate\Support\Facades\Auth;

class SignatureController extends Controller
{
    public function sign(Request $request)
    {
        $request->validate([
            'document_type' => 'required|string',
            'document_id' => 'required|integer',
            'sign_type' => 'required|string',
        ]);

        $user = Auth::user();
        
        // Define which roles are allowed for which sign_type
        $allowedRoles = [
            // Work Order
            'dikerjakan' => ['Admin', 'Super Admin', 'User'], // Or anyone who created it
            'diperiksa' => ['Foreman'],
            'ditinjau' => ['Supervisor'],
            'disetujui' => ['Superintendent', 'Manager'],
            
            // JWO
            'dibuat' => ['Admin', 'Super Admin', 'User'],
            'jwo_diperiksa' => ['Supervisor'],
            'jwo_disetujui' => ['Superintendent', 'Manager'],
            'jwo_logistik' => ['Logistik', 'Admin'],
            'dikirim' => ['Driver', 'User', 'Admin'],
            'diterima' => ['Vendor', 'User', 'Admin']
        ];

        $signType = $request->sign_type;
        $hasPermission = false;
        $matchedRole = null;

        if (array_key_exists($signType, $allowedRoles)) {
            foreach ($allowedRoles[$signType] as $role) {
                if ($user->hasRole($role)) {
                    $hasPermission = true;
                    $matchedRole = $role;
                    break;
                }
            }
        }

        // Always allow Super Admin as a fallback/override
        if (!$hasPermission && $user->hasRole('Super Admin')) {
            $hasPermission = true;
            $matchedRole = 'Super Admin';
        }

        if (!$hasPermission) {
            return back()->with('error', 'Anda tidak memiliki hak akses (Role) untuk menandatangani bagian ini.');
        }

        // Store or update the signature
        DocumentSignature::updateOrCreate(
            [
                'document_type' => $request->document_type,
                'document_id' => $request->document_id,
                'sign_type' => $signType,
            ],
            [
                'user_id' => $user->id,
                'role_name' => $matchedRole,
            ]
        );

        return back()->with('success', 'Dokumen berhasil ditandatangani.');
    }
}
