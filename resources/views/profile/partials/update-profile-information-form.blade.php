@php
    $states = [
        'AC' => 'Acre',
        'AL' => 'Alagoas',
        'AP' => 'Amapá',
        'AM' => 'Amazonas',
        'BA' => 'Bahia',
        'CE' => 'Ceará',
        'DF' => 'Distrito Federal',
        'ES' => 'Espírito Santo',
        'GO' => 'Goiás',
        'MA' => 'Maranhão',
        'MT' => 'Mato Grosso',
        'MS' => 'Mato Grosso do Sul',
        'MG' => 'Minas Gerais',
        'PA' => 'Pará',
        'PB' => 'Paraíba',
        'PR' => 'Paraná',
        'PE' => 'Pernambuco',
        'PI' => 'Piauí',
        'RJ' => 'Rio de Janeiro',
        'RN' => 'Rio Grande do Norte',
        'RS' => 'Rio Grande do Sul',
        'RO' => 'Rondônia',
        'RR' => 'Roraima',
        'SC' => 'Santa Catarina',
        'SP' => 'São Paulo',
        'SE' => 'Sergipe',
        'TO' => 'Tocantins'
    ];
@endphp

<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informações do Perfil') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __("Atualize as informações do seu perfil e endereço de email.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <!-- Nome -->
        <div>
            <x-input-label for="name" :value="__('Nome')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <!-- Email -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-gray-800">
                        {{ __('Seu endereço de email não está verificado.') }}

                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Clique aqui para reenviar o email de verificação.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('Um novo link de verificação foi enviado para seu endereço de email.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- CPF -->
        <div>
            <x-input-label for="cpf" :value="__('CPF')" />
            <x-text-input id="cpf" name="cpf" type="text" class="mt-1 block w-full" :value="old('cpf', $user->cpf)" placeholder="000.000.000-00" maxlength="14" />
            <x-input-error class="mt-2" :messages="$errors->get('cpf')" />
        </div>

        <!-- Telefone -->
        <div>
            <x-input-label for="phone" :value="__('Telefone')" />
            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" :value="old('phone', $user->phone)" placeholder="(00) 00000-0000" maxlength="15" />
            <x-input-error class="mt-2" :messages="$errors->get('phone')" />
        </div>

        <!-- CEP -->
        <div>
            <x-input-label for="zip_code" :value="__('CEP')" />
            <x-text-input id="zip_code" name="zip_code" type="text" class="mt-1 block w-full" :value="old('zip_code', $user->zip_code)" placeholder="00000-000" maxlength="9" />
            <x-input-error class="mt-2" :messages="$errors->get('zip_code')" />
        </div>

        <!-- Endereço -->
        <div>
            <x-input-label for="address" :value="__('Endereço')" />
            <x-text-input id="address" name="address" type="text" class="mt-1 block w-full" :value="old('address', $user->address)" />
            <x-input-error class="mt-2" :messages="$errors->get('address')" />
        </div>

        <!-- Número -->
        <div>
            <x-input-label for="address_number" :value="__('Número')" />
            <x-text-input id="address_number" name="address_number" type="text" class="mt-1 block w-full" :value="old('address_number', $user->address_number)" />
            <x-input-error class="mt-2" :messages="$errors->get('address_number')" />
        </div>

        <!-- Complemento -->
        <div>
            <x-input-label for="complement" :value="__('Complemento')" />
            <x-text-input id="complement" name="complement" type="text" class="mt-1 block w-full" :value="old('complement', $user->complement)" />
            <x-input-error class="mt-2" :messages="$errors->get('complement')" />
        </div>

        <!-- Bairro -->
        <div>
            <x-input-label for="neighborhood" :value="__('Bairro')" />
            <x-text-input id="neighborhood" name="neighborhood" type="text" class="mt-1 block w-full" :value="old('neighborhood', $user->neighborhood)" />
            <x-input-error class="mt-2" :messages="$errors->get('neighborhood')" />
        </div>

        <!-- Cidade -->
        <div>
            <x-input-label for="city" :value="__('Cidade')" />
            <x-text-input id="city" name="city" type="text" class="mt-1 block w-full" :value="old('city', $user->city)" />
            <x-input-error class="mt-2" :messages="$errors->get('city')" />
        </div>

        <!-- Estado -->
        <div>
            <x-input-label for="state" :value="__('Estado')" />
            <select id="state" name="state" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                <option value="">Selecione um estado</option>
                @foreach($states as $uf => $name)
                    <option value="{{ $uf }}" {{ old('state', $user->state) === $uf ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('state')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Salvar') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('Salvo.') }}</p>
            @endif
        </div>
    </form>
</section>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
<script>
    $(document).ready(function() {
        $('#cpf').mask('000.000.000-00');
        $('#phone').mask('(00) 00000-0000');
        $('#zip_code').mask('00000-000');

        $('#zip_code').on('blur', function() {
            const zip = $(this).val().replace(/\D/g, '');
            if (zip.length === 8) {
                $.get(`https://viacep.com.br/ws/${zip}/json/`)
                    .done(function(data) {
                        if (!data.erro) {
                            $('#address').val(data.logradouro);
                            $('#neighborhood').val(data.bairro);
                            $('#city').val(data.localidade);
                            $('#state').val(data.uf);
                        }
                    });
            }
        });
    });
</script>
@endpush