<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;

class AuditTrailController extends Controller
{
    public function index(Request $request)
    {
        $laboratoryId = auth()->user()->laboratory_id;

        $query = AuditLog::with(['user', 'branch'])
            ->where('laboratory_id', $laboratoryId);

        /*
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query
            ->latest()
            ->paginate(25)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Filter Options
        |--------------------------------------------------------------------------
        */

        $users = User::where('laboratory_id', $laboratoryId)
            ->orderBy('name')
            ->get();

        $actions = AuditLog::where('laboratory_id', $laboratoryId)
            ->whereNotNull('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        $modules = AuditLog::where('laboratory_id', $laboratoryId)
            ->whereNotNull('module')
            ->distinct()
            ->orderBy('module')
            ->pluck('module');

        return view('accounting.audit-trail.index', compact(
            'logs',
            'users',
            'actions',
            'modules'
        ));
    }
}