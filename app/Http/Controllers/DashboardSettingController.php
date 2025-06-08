<?php

namespace App\Http\Controllers;

use App\Models\DashboardSetting;
use Illuminate\Http\Request;

class DashboardSettingController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $settings = $user->dashboardSetting ?? new DashboardSetting(['user_id' => $user->id]);

        $availableSections = [
            'performance_metrics' => 'Métricas de Desempenho',
            'summary_cards' => 'Cards de Resumo',
            'financial_goals' => 'Objetivos Financeiros',
            'budgets' => 'Orçamentos',
            'expenses_by_category' => 'Gráfico de Despesas por Categoria',
            'cash_flow' => 'Gráfico de Fluxo de Caixa',
            'recent_transactions' => 'Transações Recentes'
        ];

        return view('settings.dashboard', compact('settings', 'availableSections'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $settings = $user->dashboardSetting ?? new DashboardSetting(['user_id' => $user->id]);
        
        // Se nenhuma seção for selecionada, o array será vazio
        $settings->visible_sections = $request->input('sections', []);
        $settings->save();

        return redirect()->route('settings.dashboard')->with('success', 'Configurações do dashboard atualizadas com sucesso!');
    }
} 