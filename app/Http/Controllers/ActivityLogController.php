<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view_activity_log')->only(['index']);
    }

    public function index(Request $request)
    {
        $query = Activity::with('causer')->latest();

        if ($request->filled('causer_id')) {
            $query->where('causer_id', $request->causer_id);
        }
        if ($request->filled('log_name')) {
            $query->where('log_name', $request->log_name);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activities = $query->paginate(20)->withQueryString();
        $users = \App\Models\User::select('id', 'nama_lengkap')->get();

        return view('activity-log.index', compact('activities', 'users'));
    }
}
