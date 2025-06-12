<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\CreditCard;

class RequireCreditCardPassword
{
    public function handle(Request $request, Closure $next)
    {
        $creditCardId = $request->route('credit_card');
        if (!$creditCardId) {
            return $next($request);
        }

        $creditCard = CreditCard::findOrFail($creditCardId);
        
        // Se a senha já foi confirmada nos últimos 10 minutos
        if ($creditCard->hasValidPasswordConfirmation()) {
            return $next($request);
        }

        // Salva a URL atual para redirecionamento posterior
        session()->put('url.intended', $request->url());
        
        // Se for uma requisição AJAX, retorna 423 (Locked)
        if ($request->ajax()) {
            return response('Confirmação de senha do cartão necessária.', 423);
        }
        
        return redirect()->route('credit-cards.confirm-password', $creditCard);
    }
} 