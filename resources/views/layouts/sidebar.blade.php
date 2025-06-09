<div
  id="sidebar"
  class="fixed inset-y-0 left-0 z-30 w-[17rem] bg-white border-r border-gray-200 transform transition-transform duration-300"
  :class="{ '-translate-x-full': !sidebarOpen }"
>           <div class="flex items-center justify-center h-16 border-b border-gray-200 bg-white shadow-sm px-4">
    <h1 class="text-2xl font-bold text-gray-800 tracking-tight flex items-center space-x-2">
      <span class="text-green-500 text-3xl">💰</span>
      <span>
        <span class="text-gray-900 font-extrabold">FINANÇAS</span>
        <span class="text-green-600 font-extrabold">TECH</span>
      </span>
    </h1>
  </div>
            <nav class="mt-5 px-2">
                <a href="{{ route('dashboard') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fas fa-home mr-3 {{ request()->routeIs('dashboard') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Dashboard
                </a>

                <a href="{{ route('accounts.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('accounts.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fas fa-wallet mr-3 {{ request()->routeIs('accounts.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Contas
                </a>

                <a href="{{ route('credit-cards.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('credit-cards.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fas fa-credit-card mr-3 {{ request()->routeIs('credit-cards.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Cartões de Crédito
                </a>

                <a href="{{ route('transactions.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('transactions.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fas fa-exchange-alt mr-3 {{ request()->routeIs('transactions.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Transações
                </a>

                <a href="{{ route('categories.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('categories.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fas fa-tags mr-3 {{ request()->routeIs('categories.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Categorias
                </a>

                <a href="{{ route('budgets.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('budgets.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fas fa-chart-pie mr-3 {{ request()->routeIs('budgets.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Orçamentos
                </a>

                <a href="{{ route('financial-goals.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('financial-goals.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fas fa-bullseye mr-3 {{ request()->routeIs('financial-goals.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Objetivos Financeiros
                </a>

                <a href="{{ route('performance.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('performance.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fas fa-chart-line mr-3 {{ request()->routeIs('performance.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Meu Desempenho
                </a>

                <a href="{{ route('reports.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('reports.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <i class="fas fa-chart-bar mr-3 {{ request()->routeIs('reports.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                    Relatórios
                </a>

                <div class="mt-8 pt-8 border-t border-gray-200">
                    <a href="{{ route('calendar.index') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('calendar.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fa-solid fa-calendar mr-3 {{ request()->routeIs('calendar.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                        Calendário
                    </a>
                    <a href="{{ route('settings.dashboard') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('settings.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fa-solid fa-gear mr-3 {{ request()->routeIs('settings.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                        Configurações
                    </a>
                    <a href="{{ route('profile.edit') }}" class="group flex items-center px-2 py-2 text-base font-medium rounded-md {{ request()->routeIs('profile.*') ? 'bg-gray-100 text-gray-900' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                        <i class="fas fa-user mr-3 {{ request()->routeIs('profile.*') ? 'text-gray-500' : 'text-gray-400 group-hover:text-gray-500' }}"></i>
                        Perfil
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="mt-1">
                        @csrf
                        <button type="submit" class="w-full group flex items-center px-2 py-2 text-base font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                            <i class="fas fa-sign-out-alt mr-3 text-gray-400 group-hover:text-gray-500"></i>
                            Sair
                        </button>
                    </form>
                </div>
            </nav>
        </div>