<?php

namespace App\Http\Middleware;

use App\Models\Laboratory;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveLaboratoryFromDomain
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = strtolower($request->getHost());

        $laboratory = Laboratory::where('custom_domain', $host)
            ->where('is_active', true)
            ->first();

          if ($laboratory) {
    $request->attributes->set('laboratory', $laboratory);

    if (auth()->check() && auth()->user()->laboratory_id !== $laboratory->id) {
        abort(403, 'You do not have access to this laboratory.');
    }
}

return $next($request);
    }
}