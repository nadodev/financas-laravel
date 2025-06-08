<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function update(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => ['required', 'exists:plans,id'],
        ]);

        $plan = Plan::findOrFail($validated['plan_id']);
        
        // Aqui você pode adicionar a lógica de pagamento se necessário
        // Por exemplo, integração com gateway de pagamento

        $request->user()->update([
            'plan_id' => $plan->id
        ]);

        return redirect()->route('plans.index')
            ->with('success', "Seu plano foi atualizado para {$plan->name} com sucesso!");
    }

    public function cancel(Request $request)
    {
        $basicPlan = Plan::where('slug', 'basic')->first();
        
        $request->user()->update([
            'plan_id' => $basicPlan->id
        ]);

        return redirect()->route('plans.index')
            ->with('success', 'Sua assinatura foi cancelada. Você voltou para o plano Básico.');
    }
} 