<?php

namespace App\Http\Middleware;

use App\Models\LaboratoryModule;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountingAccess
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        if (!$user->isAdmin() && !$user->isAccountant()) {
            abort(403, 'You do not have access to Accounting.');
        }

        $enabled = LaboratoryModule::where(
            'laboratory_id',
            $user->laboratory_id
        )
            ->where('module', 'accounting')
            ->where('enabled', true)
            ->exists();

        if (!$enabled) {
            abort(403, 'Accounting is not enabled for your laboratory.');
        }

        return $next($request);
    }
}