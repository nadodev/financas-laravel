<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run()
    {
        $plans = [
            [
                'name' => 'Básico',
                'slug' => 'basic',
                'price' => 0.00,
                'description' => 'Perfeito para começar a organizar suas finanças.',
                'features' => [
                    '1 conta bancária',
                    'Até 100 transações por mês',
                    'Categorização de despesas',
                    'Relatórios básicos',
                    'Orçamento mensal'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Essencial',
                'slug' => 'essential',
                'price' => 9.90,
                'description' => 'Recursos essenciais para um controle financeiro eficiente.',
                'features' => [
                    'Até 3 contas bancárias',
                    'Até 500 transações por mês',
                    'Categorização avançada',
                    'Relatórios detalhados',
                    'Orçamento por categoria',
                    'Metas financeiras',
                    'Cartões de crédito'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Flexível',
                'slug' => 'flexible',
                'price' => 19.90,
                'description' => 'Flexibilidade total para suas necessidades financeiras.',
                'features' => [
                    'Contas bancárias ilimitadas',
                    'Até 2000 transações por mês',
                    'Categorização personalizada',
                    'Relatórios avançados',
                    'Orçamento flexível',
                    'Metas financeiras avançadas',
                    'Cartões de crédito ilimitados',
                    'Importação de extratos',
                    'Lembretes personalizados'
                ],
                'is_active' => true
            ],
            [
                'name' => 'Avançado',
                'slug' => 'advanced',
                'price' => 39.90,
                'description' => 'Recursos completos para gestão financeira profissional.',
                'features' => [
                    'Contas bancárias ilimitadas',
                    'Transações ilimitadas',
                    'Categorização inteligente',
                    'Relatórios personalizados',
                    'Orçamento avançado',
                    'Metas financeiras ilimitadas',
                    'Cartões de crédito ilimitados',
                    'Importação automática',
                    'Lembretes avançados',
                    'Análise de investimentos',
                    'Suporte prioritário',
                    'API de integração'
                ],
                'is_active' => true
            ]
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
} 