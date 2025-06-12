<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class RequirePasswordConfirmation
{
    protected $timeout = 600; // 10 minutos em segundos

    public function handle(Request $request, Closure $next)
    {
        // Se a sessão não tem confirmação de senha ou se passou o tempo limite
        if (!session()->has('password_confirmed_at') || 
            (time() - session()->get('password_confirmed_at') > $this->timeout)) {
            
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