<div>
    
    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md mb-3" role="alert">
      <div class="flex">
        <div class="py-1"><svg class="fill-current h-6 w-6 text-teal-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
        <div>
          <p class="font-bold">Inscripciones por defecto <u>un año</u> anterior a la fecha de hoy</p>
        </div>
      </div>
    </div>

    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-2">
            <select wire:model="perPage"
                class="w-20 block p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
        </div>
        <div class="lg:w-1/2 md:w-1/2 sm:w-full my-2">
            <div class="my-2">
                <x-input wire:model.debounce.500ms="searchTerm" placeholder="{{ __('Search') }}" autofocus />
            </div>
        </div>
        <div class="grid gap-4 grid-cols-2 items-center justify-center">
            <div class="w-full mb-2 flex flex-wrap ">
                <div class="w-full md:w-1/2 px-2">
                    <label>{{ __('Start Date') }} <span class="text-red-500">*</span></label>
                    <x-input wire:model="startDate" type="date" name="startDate" value="$startDate" />
                    @error('startDate')
                        <span class="text-danger mt-1">{{ $message }}</span>
                    @enderror
                </div>
                <div class="w-full md:w-1/2 px-2">
                    <label>{{ __('End Date') }} <span class="text-red-500">*</span></label>
                    <x-input wire:model="endDate" type="date" name="endDate" value="$endDate" />
                    @error('endDate')
                        <span class="text-danger mt-1">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="gap-2 inline-flex items-center mx-0 px-2 mb-2">
                <x-button type="button" primary wire:click="filterByType('day')">{{ __('Today') }}</x-button>
                <x-button type="button" info wire:click="filterByType('month')">{{ __('This Month') }}</x-button>
                <x-button type="button" warning wire:click="filterByType('year')">{{ __('This Year') }}</x-button>
            </div>
        </div>
    </div>

    <x-table>
        <x-slot name="thead">
            {{-- <x-table.th>
                <input type="checkbox" wire:model="selectPage" />
            </x-table.th> --}}
            <x-table.th sortable wire:click="sortBy('sale_id')">
                {{ __('Sale') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('name')" :direction="$sorts['reference'] ?? null">
                {{ __('Reference') }}
            </x-table.th>
            <x-table.th>
                {{ __('Customer') }}
            </x-table.th>
            <x-table.th>
                {{ __('Discount') }}
            </x-table.th>
            <x-table.th>
                {{ __('Subtotal') }}
            </x-table.th>
            <x-table.th>
                {{ __('Remaining') }}
            </x-table.th>
            <x-table.th>
                {{ __('Expiration') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('created_at')">
                {{ __('Created At') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>

        <x-table.tbody>
            @forelse ($inscriptions as $inscription)
                <x-table.tr >
                    <x-table.td>
                        #{{ optional($inscription->sale)->id }}
                    </x-table.td>
                    <x-table.td>
                        {{ $inscription->name }}
                        <a type="button" class="bg-blue-800 border border-transparent text-white hover:bg-blue-900 focus:ring-blue-900 active:bg-blue-900 focus:outline-none focus:border-blue-900 inline-flex items-center px-4 py-2 rounded-md font-semibold text-xs uppercase tracking-widest disabled:opacity-25 transition ease-in-out duration-150" target="_blank" href="{{ route('inscription.print', $inscription->sale_id) }}">
                            <i class="fas fa-print"></i>
                        </a>
                    </x-table.td>
                    <x-table.td>
                        {{ optional($inscription->customer)->name }}
                    </x-table.td>
                    <x-table.td>
                        $ {{ $inscription->product_discount_amount * $inscription->quantity }}
                    </x-table.td>
                    <x-table.td>
                        $ <p class="text-blue-600/100 inline-block">{{ $inscription->sub_total }}</p>
                    </x-table.td>
                    <x-table.td>
                        {{ $inscription->inscription_remaining . ' '.__('days') }}
                    </x-table.td>
                    <x-table.td>
                        {{ $inscription->inscription_expiration }}
                    </x-table.td>
                    <x-table.td>
                        {{ $inscription->created_at }}
                    </x-table.td>

                    <x-table.td>
                        <x-dropdown
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-32 p-1 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                            <x-slot name="trigger">
                                <button type="button"
                                    class="px-4 text-base font-semibold text-gray-500 hover:text-sky-800 dark:text-slate-400 dark:hover:text-sky-400">
                                    <i class="fas fa-angle-double-down"></i>
                                </button>
                            </x-slot>
                            <x-slot name="content">
                                <x-dropdown-link wire:click="$emit('deleteModal', {{ $inscription->id }})"
                                    wire:loading.attr="disabled">
                                    <i class="fas fa-trash"></i>
                                    {{ __('Delete') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </x-table.td>

                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="6">
                        <div class="flex justify-center items-center">
                            <i class="fas fa-box-open text-4xl text-gray-400"></i>
                            {{ __('No results found') }}
                        </div>
                    </x-table.td>
                </x-table.tr>
            @endforelse
        </x-table.tbody>
    </x-table>

    <div class="px-6 py-3">
        {{ $inscriptions->links() }}
        @if($inscriptions->count() < $perPage && $page < 2)
            Mostrando
            {{ $inscriptions->total() }} resultados
        @endif
    </div>


</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            window.livewire.on('deleteModal', inscriptionId => {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: '¡Si, eliminar!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.livewire.emit('delete', inscriptionId)
                    }
                })
            })
        })
    </script>
@endpush

