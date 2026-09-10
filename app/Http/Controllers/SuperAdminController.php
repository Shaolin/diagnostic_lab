<?php
namespace App\Http\Controllers;



use App\Models\Laboratory;

class SuperAdminController extends Controller
{
    public function index()
    {
        $laboratories = Laboratory::query()
            ->with(['users' => function ($query) {
                $query->where('role', 'admin');
            }])
            ->withCount('users')
            ->latest()
            ->paginate(10);

        $totalLaboratories = Laboratory::count();

        $activeLaboratories = Laboratory::where('is_active', true)->count();

        $inactiveLaboratories = Laboratory::where('is_active', false)->count();

        $totalUsers = \App\Models\User::count();

        return view('super-admin.dashboard', compact(
            'laboratories',
            'totalLaboratories',
            'activeLaboratories',
            'inactiveLaboratories',
            'totalUsers'
        ));
    }
}

