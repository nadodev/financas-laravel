<?php

namespace App\Http\Controllers;

use App\Models\Plan;

class LandingPageController extends Controller
{
    public function index()
    {
        $features = [
            [
                'icon' => 'chart-line',
                'title' => 'Dashboard Inteligente',
                'description' => 'Visualize suas finanças em tempo real com gráficos e indicadores intuitivos.'
            ],
            [
                'icon' => 'wallet',
                'title' => 'Gestão de Transações',
                'description' => 'Registre e categorize suas receitas e despesas de forma simples e rápida.'
            ],
            [
                'icon' => 'bullseye',
                'title' => 'Objetivos Financeiros',
                'description' => 'Defina metas, acompanhe seu progresso e realize seus sonhos.'
            ],
            [
                'icon' => 'calculator',
                'title' => 'Orçamentos Inteligentes',
                'description' => 'Crie e gerencie orçamentos por categoria com alertas automáticos.'
            ],
            [
                'icon' => 'chart-pie',
                'title' => 'Análises Detalhadas',
                'description' => 'Relatórios e análises para entender seus padrões de gastos.'
            ],
            [
                'icon' => 'mobile',
                'title' => 'Acesso em Qualquer Lugar',
                'description' => 'Interface responsiva para usar em qualquer dispositivo.'
            ]
        ];

        $plans = Plan::all()->map(function ($plan) {
            return [
                'name' => $plan->name,
                'price' => number_format($plan->price, 2, ',', '.'),
                'period' => 'mês',
                'description' => $plan->description,
                'features' => $plan->features,
                'highlight' => $plan->slug === 'essential',
                'cta' => $plan->price > 0 ? 'Assinar ' . $plan->name : 'Começar Grátis'
            ];
        });

        $testimonials = [
            [
                'name' => 'Maria Silva',
                'role' => 'Profissional Autônoma',
                'photo' => 'https://i.pravatar.cc/150?img=1',
                'text' => 'Com este sistema, finalmente consegui organizar minhas finanças e realizar o sonho da casa própria!'
            ],
            [
                'name' => 'João Santos',
                'role' => 'Empresário',
                'photo' => 'https://i.pravatar.cc/150?img=2',
                'text' => 'A gestão financeira da minha empresa mudou completamente. Os relatórios são fantásticos!'
            ],
            [
                'name' => 'Ana Costa',
                'role' => 'Investidora',
                'photo' => 'https://i.pravatar.cc/150?img=3',
                'text' => 'O controle de objetivos me ajudou a planejar e alcançar minha independência financeira.'
            ]
        ];

        $faqs = [
            [
                'question' => 'Como começar a usar o sistema?',
                'answer' => 'Basta criar uma conta gratuita e você já pode começar a registrar suas transações e organizar suas finanças.'
            ],
            [
                'question' => 'Quais são as diferenças entre os planos?',
                'answer' => 'O plano Básico é gratuito e oferece funcionalidades essenciais. Os planos pagos oferecem recursos adicionais como múltiplas contas, relatórios avançados e suporte prioritário.'
            ],
            [
                'question' => 'Posso mudar de plano depois?',
                'answer' => 'Sim! Você pode fazer upgrade ou downgrade do seu plano a qualquer momento, conforme suas necessidades.'
            ],
            [
                'question' => 'O sistema é seguro?',
                'answer' => 'Sim! Utilizamos criptografia de ponta a ponta e seguimos as melhores práticas de segurança do mercado.'
            ],
            [
                'question' => 'Como funciona o suporte?',
                'answer' => 'Oferecemos diferentes níveis de suporte conforme seu plano, desde suporte por email até atendimento 24/7 com consultor dedicado.'
            ]
        ];

        return view('landing-page', compact('features', 'plans', 'testimonials', 'faqs'));
    }
} 