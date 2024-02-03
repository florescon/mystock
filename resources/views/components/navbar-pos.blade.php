<section class="py-5 px-6 bg-gray-50 shadow">
    <nav class="flex items-center justify-between flex-shrink-0 px-3">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="text-xl font-semibold">
            <img class="w-14 h-auto" src="{{ asset('images/logo.png') }}" alt="Site Logo">
            <span class="sr-only">{{ config('settings.site_title') }}</span>
        </a>

        <div class="flex gap-4">
            <div class="md:flex hidden">
                <x-button-fullscreen />
            </div>

            <x-language-dropdown />

            {{-- @can('show_notifications')
                <div class="md:flex hidden flex-wrap items-center">
                    @livewire('notifications')
                </div>
            @endcan --}}

            <a type="button" class="bg-gray-500 border border-transparent text-white hover:bg-gray-600 focus:ring-gray-500 active:bg-gray-900 focus:outline-none focus:border-gray-900 inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs uppercase tracking-widest disabled:opacity-25 transition ease-in-out duration-150" target="_blank" href="{{ url('/') }}/consentimiento.pdf">
                {{ __('Consent') }}&nbsp; <i class="fas fa-print"></i>
            </a>

            @if(\App\Models\SaleDetailsService::where('service_id', 1)->latest()->first())
                @php( $last = \App\Models\SaleDetailsService::where('service_id', 1)->latest()->first() )
                @if($last->sale_id)
                    <a type="button" class="bg-blue-800 border border-transparent text-white hover:bg-blue-900 focus:ring-blue-900 active:bg-blue-900 focus:outline-none focus:border-blue-900 inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs uppercase tracking-widest disabled:opacity-25 transition ease-in-out duration-150" target="_blank" href="{{ route('inscription.print', $last->sale_id) }}">
                        {{ __('Print Last Inscription') }}&nbsp; <i class="fas fa-print"></i>
                    </a>
                @endif
            @endif

            @if(\App\Models\Sale::orderBy('id', 'DESC')->first())
                <a type="button" class="bg-red-800 border border-transparent text-white hover:bg-red-900 focus:ring-red-900 active:bg-red-900 focus:outline-none focus:border-red-900 inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs uppercase tracking-widest disabled:opacity-25 transition ease-in-out duration-150 mr-9" target="_blank" href="{{ route('sales.pos.pdf', \App\Models\Sale::orderBy('id', 'DESC')->first()->id) }}">
                    {{ __('Print Last Sale') }}&nbsp; <i class="fas fa-print"></i>
                </a>
            @endif

            <x-button type="button" primary onclick="Livewire.emit('createAssociate')">
                {{ __('Services') }}
            </x-button>

            <x-dropdown align="right" width="56">
                <x-slot name="trigger">
                    <x-button type="button" secondary>
                        <x-icons.menu class="w-4 h-4" />
                    </x-button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('home')">
                        {{ __('Dashboard') }}
                    </x-dropdown-link>

                    {{-- <x-dropdown-link onclick="Livewire.emit('createModal')">
                        {{ __('Create Product') }}
                    </x-dropdown-link> --}}

                    <x-dropdown-link onclick="Livewire.emit('createCustomer')">
                        {{ __('Create Customer') }}
                    </x-dropdown-link>

                    <x-dropdown-link onclick="Livewire.emit('recentSales')">
                        {{ __('Recent Sales') }}
                    </x-dropdown-link>
                </x-slot>
            </x-dropdown>
        </div>
    </nav>
</section>
