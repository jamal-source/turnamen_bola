<?php

namespace App\Http\Middleware;

use App\Models\Operator;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOperator
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->session()->has('operator_id')) {
            return redirect()->route('operator.login')->with('error', 'Silakan login sebagai Operator SSB terlebih dahulu.');
        }

        $operator = Operator::find($request->session()->get('operator_id'));
        if (! $operator || $operator->status !== 'active') {
            $request->session()->forget('operator_id');

            return redirect()->route('operator.login')->with('error', 'Akun Operator tidak ditemukan atau tidak aktif.');
        }

        // Share operator model with request / views
        $request->attributes->set('operator', $operator);

        return $next($request);
    }
}
