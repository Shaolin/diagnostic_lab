<?php

namespace App\Http\Controllers;

use App\Models\Laboratory;
use App\Models\LaboratoryModule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SuperAdminModuleController extends Controller
{
    public function edit(Laboratory $laboratory): View
    {
        $modules = [
            'accounting' => 'Accounting',
            'customer_service' => 'Customer Service',
        ];

        $enabledModules = $laboratory->modules()
            ->where('enabled', true)
            ->pluck('module')
            ->toArray();

        return view('super-admin.modules.edit', compact(
            'laboratory',
            'modules',
            'enabledModules'
        ));
    }

    public function update(
        Request $request,
        Laboratory $laboratory
    ): RedirectResponse {
        $modules = [
            'accounting',
            'customer_service',
        ];

        $enabledModules = $request->input('modules', []);

        $enabledModules = array_values(
            array_intersect($enabledModules, $modules)
        );

        foreach ($modules as $module) {
            LaboratoryModule::updateOrCreate(
                [
                    'laboratory_id' => $laboratory->id,
                    'module' => $module,
                ],
                [
                    'enabled' => in_array($module, $enabledModules),
                ]
            );
        }

        return redirect()
            ->route('super-admin.dashboard')
            ->with('success', 'Laboratory modules updated successfully.');
    }
}