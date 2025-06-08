<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinanceApp - Gestão Financeira Inteligente</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="stylesheet" href="{{ asset('build/assets/app-DVuR1H4_.css') }}">
   

    <style>
        .gradient-text {
            background: linear-gradient(45deg, #3B82F6, #2563EB);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239BA3AF' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .feature-card {
            transition: all 0.3s ease;
        }
        .feature-card:hover {
            transform: translateY(-5px);
        }
        .pricing-card {
            transition: all 0.3s ease;
        }
        .pricing-card:hover {
            transform: translateY(-5px);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header/Navigation -->
    <header class="bg-white/80 backdrop-blur-md shadow-sm fixed w-full z-50">
        <nav class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold gradient-text">FinanceApp</a>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-gray-600 hover:text-blue-600 transition-colors">Funcionalidades</a>
                    <a href="#plans" class="text-gray-600 hover:text-blue-600 transition-colors">Planos</a>
                    <a href="#testimonials" class="text-gray-600 hover:text-blue-600 transition-colors">Depoimentos</a>
                    <a href="#faq" class="text-gray-600 hover:text-blue-600 transition-colors">FAQ</a>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-full hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-600 hover:text-blue-600 transition-colors">Entrar</a>
                        <a href="{{ route('register') }}" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-full hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl">
                            Criar Conta
                        </a>
                    @endauth
                </div>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="pt-32 pb-20 hero-pattern">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="md:w-1/2 mb-12 md:mb-0" data-aos="fade-right">
                    <h1 class="text-5xl md:text-6xl font-bold mb-6 leading-tight">
                        Controle <span class="gradient-text">Financeiro</span> para o seu Sucesso
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        Transforme sua relação com o dinheiro. Nossa plataforma oferece ferramentas inteligentes para você alcançar seus objetivos financeiros.
                    </p>
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('register') }}" class="px-8 py-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-full hover:from-blue-700 hover:to-blue-800 transition-all shadow-lg hover:shadow-xl text-center">
                            Começar Grátis
                        </a>
                        <a href="#features" class="px-8 py-4 bg-white text-blue-600 rounded-full hover:bg-gray-50 transition-all shadow-md hover:shadow-lg text-center">
                            Conhecer Mais
                        </a>
                    </div>
                </div>
                <div class="md:w-1/2 relative" data-aos="fade-left">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-blue-700/20 rounded-3xl transform rotate-6"></div>
                    <img src="https://placehold.co/800x600/3B82F6/FFFFFF/png?text=Dashboard+Preview&font=Montserrat" alt="Dashboard Preview" class="rounded-3xl shadow-2xl relative z-10">
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="text-4xl font-bold text-blue-600 mb-2">10k+</div>
                    <div class="text-gray-600">Usuários Ativos</div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="text-4xl font-bold text-blue-600 mb-2">R$5M+</div>
                    <div class="text-gray-600">Economizados</div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="300">
                    <div class="text-4xl font-bold text-blue-600 mb-2">98%</div>
                    <div class="text-gray-600">Satisfação</div>
                </div>
                <div class="text-center" data-aos="fade-up" data-aos-delay="400">
                    <div class="text-4xl font-bold text-blue-600 mb-2">24/7</div>
                    <div class="text-gray-600">Suporte</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold mb-4">Funcionalidades Poderosas</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Ferramentas inteligentes que transformam a maneira como você gerencia suas finanças
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($features as $feature)
                    <div class="feature-card bg-white p-8 rounded-2xl shadow-lg" data-aos="fade-up">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-6 transform -rotate-6">
                            <i class="fas fa-{{ $feature['icon'] }} text-2xl text-white"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">{{ $feature['title'] }}</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $feature['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Plans Section -->
    <section id="plans" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold mb-4">Planos para Todos</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Escolha o plano ideal para suas necessidades e comece a transformar sua vida financeira
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($plans as $plan)
                    <div class="pricing-card bg-white rounded-2xl shadow-lg overflow-hidden {{ $plan['highlight'] ? 'ring-2 ring-blue-600' : '' }}" data-aos="fade-up">
                        <div class="p-8">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-2xl font-bold text-gray-900">{{ $plan['name'] }}</h3>
                                @if($plan['highlight'])
                                    <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-sm font-semibold">Popular</span>
                                @endif
                            </div>
                            <div class="flex items-baseline mb-8">
                                <span class="text-4xl font-bold gradient-text">R$ {{ $plan['price'] }}</span>
                                <span class="text-gray-600 ml-2">/{{ $plan['period'] }}</span>
                            </div>
                            <ul class="space-y-4 mb-8">
                                @foreach($plan['features'] as $feature)
                                    <li class="flex items-center">
                                        <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                        <span class="text-gray-600">{{ $feature }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            <a href="{{ route('register') }}" class="block w-full py-4 px-6 text-center {{ $plan['highlight'] ? 'bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-900' }} rounded-full transition-all shadow-lg hover:shadow-xl font-semibold">
                                {{ $plan['cta'] }}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold mb-4">O que Nossos Usuários Dizem</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Histórias reais de pessoas que transformaram suas finanças com nossa plataforma
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($testimonials as $testimonial)
                    <div class="bg-white p-8 rounded-2xl shadow-lg" data-aos="fade-up">
                        <div class="flex items-center mb-6">
                            <img src="{{ $testimonial['photo'] }}" alt="{{ $testimonial['name'] }}" class="w-16 h-16 rounded-full ring-4 ring-blue-100">
                            <div class="ml-4">
                                <h4 class="text-lg font-semibold text-gray-900">{{ $testimonial['name'] }}</h4>
                                <p class="text-blue-600">{{ $testimonial['role'] }}</p>
                            </div>
                        </div>
                        <p class="text-gray-600 leading-relaxed">"{{ $testimonial['text'] }}"</p>
                        <div class="mt-6 flex text-yellow-400">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section id="faq" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16" data-aos="fade-up">
                <h2 class="text-4xl font-bold mb-4">Perguntas Frequentes</h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Tire suas dúvidas sobre nossa plataforma
                </p>
            </div>
            <div class="max-w-3xl mx-auto">
                @foreach($faqs as $faq)
                    <div class="mb-6 bg-gray-50 rounded-2xl p-6 hover:bg-gray-100 transition-colors" data-aos="fade-up">
                        <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ $faq['question'] }}</h3>
                        <p class="text-gray-600 leading-relaxed">{{ $faq['answer'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-blue-600 to-blue-700">
        <div class="container mx-auto px-4 text-center" data-aos="fade-up">
            <h2 class="text-4xl font-bold text-white mb-8">
                Pronto para Transformar suas Finanças?
            </h2>
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('register') }}" class="px-8 py-4 bg-white text-blue-600 rounded-full hover:bg-blue-50 transition-all shadow-lg hover:shadow-xl font-semibold">
                    Criar Conta Grátis
                </a>
                <a href="#plans" class="px-8 py-4 border-2 border-white text-white rounded-full hover:bg-white/10 transition-all font-semibold">
                    Conhecer Planos
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
                <div>
                    <h4 class="text-2xl font-bold gradient-text mb-4">FinanceApp</h4>
                    <p class="text-gray-400 leading-relaxed">
                        Sua plataforma completa de gestão financeira pessoal e empresarial.
                    </p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Links Rápidos</h4>
                    <ul class="space-y-2">
                        <li><a href="#features" class="text-gray-400 hover:text-white transition-colors">Funcionalidades</a></li>
                        <li><a href="#plans" class="text-gray-400 hover:text-white transition-colors">Planos</a></li>
                        <li><a href="#testimonials" class="text-gray-400 hover:text-white transition-colors">Depoimentos</a></li>
                        <li><a href="#faq" class="text-gray-400 hover:text-white transition-colors">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Legal</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Termos de Uso</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Política de Privacidade</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Cookies</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Contato</h4>
                    <ul class="space-y-2">
                        <li class="flex items-center text-gray-400">
                            <i class="far fa-envelope mr-2"></i>
                            contato@financeapp.com
                        </li>
                        <li class="flex items-center text-gray-400">
                            <i class="far fa-phone mr-2"></i>
                            (11) 99999-9999
                        </li>
                        <li class="flex space-x-4 mt-4">
                            <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center text-gray-400 hover:text-white hover:bg-gray-700 transition-colors">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center text-gray-400 hover:text-white hover:bg-gray-700 transition-colors">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center text-gray-400 hover:text-white hover:bg-gray-700 transition-colors">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-800 rounded-full flex items-center justify-center text-gray-400 hover:text-white hover:bg-gray-700 transition-colors">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} FinanceApp. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- AOS Animations -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script src="{{ asset('build/assets/app-Bf4POITK.js') }}"></script>
    <script>
        AOS.init({
            duration: 1000,
            once: true,
        });

        // Smooth Scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html> 
</html> 