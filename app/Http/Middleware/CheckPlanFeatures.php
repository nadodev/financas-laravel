<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPlanFeatures
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        // Se for uma listagem de contas, permite para todos os usuários
        if ($feature === 'multiple_accounts' && 
            $request->route()->getName() === 'accounts.index') {
            return $next($request);
        }

        // Se for criação/edição de conta, verifica o limite
        if ($feature === 'multiple_accounts' && 
            in_array($request->route()->getName(), ['accounts.store', 'accounts.update']) &&
            !$request->user()->checkAccountLimit()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Você atingiu o limite de contas para seu plano atual.',
                ], 403);
            }

            return redirect()->route('plans.index')
                ->with('error', 'Você atingiu o limite de contas para seu plano atual. Faça um upgrade para adicionar mais contas.');
        }

        // Para outras features ou operações, mantém a verificação normal
        if (!$request->user() || !$request->user()->hasFeature($feature)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Esta funcionalidade não está disponível no seu plano atual.',
                    'required_feature' => $feature
                ], 403);
            }

            return redirect()->route('plans.index')
                ->with('error', 'Esta funcionalidade requer um plano superior. Por favor, faça um upgrade do seu plano.');
        }

        return $next($request);
    }
} 