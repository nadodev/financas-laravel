<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->isMethod('GET')) {
            return $next($request);
        }

        if (!$request->user()->checkAccountLimit()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Você atingiu o limite de contas para seu plano atual.',
                ], 403);
            }

            return redirect()->route('plans.index')
                ->with('error', 'Você atingiu o limite de contas para seu plano atual. Faça um upgrade para adicionar mais contas.');
        }

        return $next($request);
    }
} 