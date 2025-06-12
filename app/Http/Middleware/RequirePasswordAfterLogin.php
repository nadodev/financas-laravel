<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequirePasswordAfterLogin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && !session()->has('password_confirmed_at')) {
            // Salva a URL atual para redirecionamento posterior
            session()->put('url.intended', $request->url());
            
            // Se for uma requisição AJAX, retorna 423 (Locked)
            if ($request->ajax()) {
                return response('Confirmação de senha necessária.', 423);
            }
            
            return redirect()->route('password.confirm');
        }

        return $next($request);
    }
} 