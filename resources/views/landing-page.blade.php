<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinanceApp - Controle Financeiro</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .primary-gradient {
            background: linear-gradient(135deg, #6366F1, #4F46E5);
        }
        .secondary-gradient {
            background: linear-gradient(135deg, #EC4899, #D946EF);
        }
        .gradient-text {
            background: linear-gradient(135deg, #6366F1, #EC4899);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="fixed w-full bg-white/90 backdrop-blur-sm border-b border-gray-100 z-50">
        <nav class="container mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="flex items-center space-x-2">
                <i class="fas fa-chart-line text-2xl text-indigo-600"></i>
                <span class="text-xl font-bold text-gray-900">FinanceApp</span>
            </a>
            
            <div class="hidden md:flex items-center space-x-8">
                <a href="#como-funciona" class="text-gray-600 hover:text-indigo-600 transition-colors">Como Funciona</a>
                <a href="#recursos" class="text-gray-600 hover:text-indigo-600 transition-colors">Recursos</a>
                <a href="#planos" class="text-gray-600 hover:text-indigo-600 transition-colors">Planos</a>
            </div>

            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-6 py-2 primary-gradient text-white rounded-lg hover:opacity-90 transition-opacity">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-indigo-600 transition-colors">Entrar</a>
                    <a href="{{ route('register') }}" class="px-6 py-2 primary-gradient text-white rounded-lg hover:opacity-90 transition-opacity">
                        Começar Grátis
                    </a>
                @endauth
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 overflow-hidden">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <span class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-full text-sm font-medium">
                        Controle financeiro simplificado
                    </span>
                    <h1 class="text-5xl lg:text-6xl font-bold mt-6 mb-6 leading-tight">
                        Transforme suas <span class="gradient-text">finanças</span> em resultados
                    </h1>
                    <p class="text-xl text-gray-600 mb-8">
                        Organize suas finanças pessoais com uma plataforma moderna e intuitiva. 
                        Tenha controle total dos seus gastos e investimentos.
                    </p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('register') }}" class="px-8 py-4 primary-gradient text-white rounded-xl hover:opacity-90 transition-opacity text-center">
                            Comece Agora
                        </a>
                        <a href="#como-funciona" class="px-8 py-4 bg-white border-2 border-indigo-600 text-indigo-600 rounded-xl hover:bg-indigo-50 transition-colors text-center">
                            Saiba Mais
                        </a>
                    </div>
                </div>
                <div class="relative">
                    <div class="absolute -inset-4 bg-gradient-to-r from-indigo-500/20 to-pink-500/20 rounded-2xl transform rotate-3"></div>
                    <img src="https://placehold.co/800x600/6366F1/FFFFFF/png?text=Dashboard+Preview&font=Montserrat" alt="Dashboard Preview" class="relative rounded-2xl shadow-xl">
                </div>
            </div>
        </div>
    </section>

    <!-- Como Funciona Section -->
    <section id="como-funciona" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-bold mb-4">Como Funciona</h2>
                <p class="text-gray-600">Comece a controlar suas finanças em três passos simples</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center card-hover">
                    <div class="w-16 h-16 primary-gradient rounded-2xl flex items-center justify-center mx-auto mb-6 transform -rotate-6">
                        <i class="fas fa-user-plus text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">1. Crie sua conta</h3>
                    <p class="text-gray-600">Cadastre-se gratuitamente em menos de 2 minutos</p>
                </div>

                <div class="text-center card-hover">
                    <div class="w-16 h-16 secondary-gradient rounded-2xl flex items-center justify-center mx-auto mb-6 transform -rotate-6">
                        <i class="fas fa-wallet text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">2. Adicione suas contas</h3>
                    <p class="text-gray-600">Conecte suas contas bancárias e cartões</p>
                </div>

                <div class="text-center card-hover">
                    <div class="w-16 h-16 primary-gradient rounded-2xl flex items-center justify-center mx-auto mb-6 transform -rotate-6">
                        <i class="fas fa-chart-line text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">3. Acompanhe tudo</h3>
                    <p class="text-gray-600">Visualize relatórios e controle seus gastos</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Recursos Section -->
    <section id="recursos" class="py-20 bg-gradient-to-b from-indigo-50 to-pink-50">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-bold mb-4">Recursos do Sistema</h2>
                <p class="text-gray-600">Tudo que você precisa para uma gestão financeira completa</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-xl shadow-sm card-hover">
                    <div class="w-12 h-12 primary-gradient rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-chart-pie text-xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Dashboard Completo</h3>
                    <p class="text-gray-600">Visão geral das suas finanças com gráficos interativos e análises detalhadas.</p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-sm card-hover">
                    <div class="w-12 h-12 secondary-gradient rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-money-bill-wave text-xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Controle de Gastos</h3>
                    <p class="text-gray-600">Categorize despesas, defina orçamentos e receba alertas de gastos.</p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-sm card-hover">
                    <div class="w-12 h-12 primary-gradient rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-bullseye text-xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Metas Financeiras</h3>
                    <p class="text-gray-600">Estabeleça objetivos e acompanhe seu progresso com metas inteligentes.</p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-sm card-hover">
                    <div class="w-12 h-12 secondary-gradient rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-file-invoice-dollar text-xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Relatórios Detalhados</h3>
                    <p class="text-gray-600">Relatórios personalizados com exportação para PDF e Excel.</p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-sm card-hover">
                    <div class="w-12 h-12 primary-gradient rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-sync text-xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Sincronização</h3>
                    <p class="text-gray-600">Sincronização automática com suas contas bancárias e investimentos.</p>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-sm card-hover">
                    <div class="w-12 h-12 secondary-gradient rounded-xl flex items-center justify-center mb-6">
                        <i class="fas fa-mobile-alt text-xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-4">Acesso Mobile</h3>
                    <p class="text-gray-600">Acesse suas finanças de qualquer lugar através do seu celular.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Planos Section -->
    <section id="planos" class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-3xl font-bold mb-4">Planos para Todos</h2>
                <p class="text-gray-600">Escolha o plano ideal para suas necessidades</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-8 rounded-xl shadow-sm border-2 border-gray-100 card-hover">
                    <div class="text-center">
                        <h3 class="text-2xl font-bold mb-2">Básico</h3>
                        <div class="text-4xl font-bold mb-4">
                            <span class="text-gray-900">Grátis</span>
                        </div>
                        <p class="text-gray-600 mb-6">Perfeito para começar</p>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span class="text-gray-600">Dashboard básico</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span class="text-gray-600">Até 2 contas</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span class="text-gray-600">Relatórios simples</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center px-6 py-3 border-2 border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-50 transition-colors">
                        Começar Grátis
                    </a>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-lg border-2 border-indigo-600 card-hover transform scale-105">
                    <div class="text-center">
                        <span class="px-4 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm font-medium">Popular</span>
                        <h3 class="text-2xl font-bold mt-4 mb-2">Pro</h3>
                        <div class="text-4xl font-bold mb-4">
                            <span class="text-gray-900">R$19,90</span>
                            <span class="text-gray-500 text-base font-normal">/mês</span>
                        </div>
                        <p class="text-gray-600 mb-6">Para uso pessoal avançado</p>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span class="text-gray-600">Tudo do plano Básico</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span class="text-gray-600">Contas ilimitadas</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span class="text-gray-600">Relatórios avançados</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span class="text-gray-600">Metas financeiras</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center px-6 py-3 primary-gradient text-white rounded-lg hover:opacity-90 transition-opacity">
                        Escolher Pro
                    </a>
                </div>

                <div class="bg-white p-8 rounded-xl shadow-sm border-2 border-gray-100 card-hover">
                    <div class="text-center">
                        <h3 class="text-2xl font-bold mb-2">Business</h3>
                        <div class="text-4xl font-bold mb-4">
                            <span class="text-gray-900">R$49,90</span>
                            <span class="text-gray-500 text-base font-normal">/mês</span>
                        </div>
                        <p class="text-gray-600 mb-6">Para empresas</p>
                    </div>
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span class="text-gray-600">Tudo do plano Pro</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span class="text-gray-600">Multi-usuários</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span class="text-gray-600">API de integração</span>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            <span class="text-gray-600">Suporte prioritário</span>
                        </li>
                    </ul>
                    <a href="{{ route('register') }}" class="block text-center px-6 py-3 border-2 border-indigo-600 text-indigo-600 rounded-lg hover:bg-indigo-50 transition-colors">
                        Contatar Vendas
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-indigo-600 to-pink-600">
        <div class="container mx-auto px-6">
            <div class="max-w-4xl mx-auto text-center text-white">
                <h2 class="text-3xl font-bold mb-4">
                    Pronto para transformar suas finanças?
                </h2>
                <p class="text-xl mb-8 opacity-90">
                    Junte-se a milhares de usuários que já estão no controle de suas finanças
                </p>
                <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-white text-indigo-600 rounded-xl hover:bg-opacity-90 transition-opacity font-semibold">
                    Criar Conta Grátis
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <i class="fas fa-chart-line text-2xl text-indigo-400"></i>
                        <span class="text-xl font-bold text-white">FinanceApp</span>
                    </div>
                    <p class="text-gray-400">
                        Sua plataforma completa de gestão financeira pessoal.
                    </p>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Produto</h4>
                    <ul class="space-y-2">
                        <li><a href="#recursos" class="text-gray-400 hover:text-white transition-colors">Recursos</a></li>
                        <li><a href="#planos" class="text-gray-400 hover:text-white transition-colors">Preços</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Novidades</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Suporte</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Ajuda</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Tutorial</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Contato</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold mb-4">Legal</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Termos</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Privacidade</a></li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} FinanceApp. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>
</body>
</html> 