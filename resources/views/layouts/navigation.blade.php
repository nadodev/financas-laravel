                    <x-nav-link :href="route('credit-cards.index')" :active="request()->routeIs('credit-cards.*')">
                        {{ __('Cartões de Crédito') }}
                    </x-nav-link>

                    <x-nav-link :href="route('calendar.index')" :active="request()->routeIs('calendar.*')">
                        {{ __('Calendário') }}
                    </x-nav-link> 