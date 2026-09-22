<?php

namespace App\Http\Middleware;

use App\Models\LaboratoryModule;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureLaboratoryModuleEnabled
{
    public function handle(
        Request $request,
        Closure $next,
        string $module
    ): Response {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        $enabled = LaboratoryModule::where(
            'laboratory_id',
            $user->laboratory_id
        )
            ->where('module', $module)
            ->where('enabled', true)
            ->exists();

        if (!$enabled) {
            abort(403, 'This module is not enabled for your laboratory.');
        }

        return $next($request);
    }
}