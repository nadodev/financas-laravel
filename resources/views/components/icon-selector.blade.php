@props(['name' => 'icon', 'value' => '', 'required' => false])

<div x-data="iconSelector()" class="mt-1">
    <div class="flex items-start space-x-3">
        <div class="relative flex-1">
            <input type="text" 
                   name="{{ $name }}" 
                   id="{{ $name }}" 
                   x-model="selectedIcon"
                   {{ $required ? 'required' : '' }}
                   class="block w-full pr-10 rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500"
                   readonly>
            
            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                <button type="button" @click="openModal" class="text-gray-400 hover:text-gray-500">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>

        <!-- Preview do ícone selecionado -->
        <div class="flex items-center justify-center w-10 h-10 rounded-md border border-gray-300 bg-white" x-show="selectedIcon">
            <i :class="selectedIcon" class="text-xl text-gray-600"></i>
        </div>
    </div>

    <!-- Modal do Seletor de Ícones -->
    <div x-show="isOpen" 
         class="fixed inset-0 z-50 overflow-y-auto" 
         x-cloak
         @keydown.escape.window="closeModal">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal"></div>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                Selecione um Ícone
                            </h3>
                            
                            <!-- Barra de Pesquisa -->
                            <div class="mt-4 relative">
                                <input type="text" 
                                       x-model="searchQuery" 
                                       placeholder="Pesquisar ícones..."
                                       class="block w-full rounded-md border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>

                            <!-- Grade de Ícones -->
                            <div class="mt-4 grid grid-cols-6 gap-4 max-h-96 overflow-y-auto">
                                <template x-for="icon in filteredIcons" :key="icon">
                                    <button type="button"
                                            @click="selectIcon(icon)"
                                            class="p-3 flex flex-col items-center justify-center space-y-2 rounded-lg hover:bg-gray-100 transition-colors"
                                            :class="{ 'bg-blue-50 ring-2 ring-blue-500': selectedIcon === icon }">
                                        <i :class="icon" class="text-2xl text-gray-600"></i>
                                        <span class="text-xs text-gray-500 truncate w-full text-center" x-text="icon"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" 
                            @click="closeModal"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Confirmar
                    </button>
                    <button type="button" 
                            @click="closeModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function iconSelector() {
    return {
        isOpen: false,
        searchQuery: '',
        selectedIcon: @json($value),
        icons: [
            'fas fa-home', 'fas fa-user', 'fas fa-cog', 'fas fa-envelope', 'fas fa-bell',
            'fas fa-star', 'fas fa-heart', 'fas fa-bookmark', 'fas fa-calendar', 'fas fa-clock',
            'fas fa-shopping-cart', 'fas fa-money-bill', 'fas fa-credit-card', 'fas fa-wallet',
            'fas fa-piggy-bank', 'fas fa-coins', 'fas fa-dollar-sign', 'fas fa-chart-line',
            'fas fa-chart-bar', 'fas fa-chart-pie', 'fas fa-percentage', 'fas fa-tags',
            'fas fa-store', 'fas fa-shopping-bag', 'fas fa-gift', 'fas fa-car', 'fas fa-plane',
            'fas fa-train', 'fas fa-bus', 'fas fa-taxi', 'fas fa-bicycle', 'fas fa-walking',
            'fas fa-utensils', 'fas fa-coffee', 'fas fa-pizza-slice', 'fas fa-hamburger',
            'fas fa-ice-cream', 'fas fa-beer', 'fas fa-wine-glass', 'fas fa-cocktail',
            'fas fa-hospital', 'fas fa-clinic-medical', 'fas fa-pills', 'fas fa-prescription',
            'fas fa-graduation-cap', 'fas fa-book', 'fas fa-laptop', 'fas fa-desktop',
            'fas fa-mobile-alt', 'fas fa-tablet-alt', 'fas fa-tv', 'fas fa-gamepad',
            'fas fa-dumbbell', 'fas fa-running', 'fas fa-swimming-pool', 'fas fa-futbol',
            'fas fa-basketball-ball', 'fas fa-baseball-ball', 'fas fa-volleyball-ball',
            'fas fa-guitar', 'fas fa-music', 'fas fa-film', 'fas fa-camera', 'fas fa-video',
            'fas fa-paint-brush', 'fas fa-palette', 'fas fa-theater-masks', 'fas fa-ticket-alt',
            'fas fa-bed', 'fas fa-couch', 'fas fa-chair', 'fas fa-bath', 'fas fa-toilet',
            'fas fa-lightbulb', 'fas fa-fan', 'fas fa-temperature-high', 'fas fa-snowflake',
            'fas fa-tshirt', 'fas fa-socks', 'fas fa-shoe-prints', 'fas fa-hat-wizard',
            'fas fa-glasses', 'fas fa-ring', 'fas fa-gem', 'fas fa-crown', 'fas fa-dog',
            'fas fa-cat', 'fas fa-fish', 'fas fa-paw', 'fas fa-bone', 'fas fa-feather'
        ],
        get filteredIcons() {
            return this.searchQuery === ''
                ? this.icons
                : this.icons.filter(icon => 
                    icon.toLowerCase().includes(this.searchQuery.toLowerCase())
                  );
        },
        openModal() {
            this.isOpen = true;
        },
        closeModal() {
            this.isOpen = false;
        },
        selectIcon(icon) {
            this.selectedIcon = icon;
            this.closeModal();
        }
    }
}
</script>
@endpush 