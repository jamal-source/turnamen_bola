<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check() || ! Auth::user()->isSuperAdmin()) {
            return redirect()->route('admin.login')->with('error', 'Silakan login sebagai Super Admin terlebih dahulu.');
        }

        return $next($request);
    }
}
