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
            <x-table.th sortable wire:click="sortBy('sale_id')">
                {{ __('Sale') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('created_at')">
                {{ __('Date') }}
            </x-table.th>
        </x-slot>

        <x-table.tbody>
            @forelse ($inscriptions as $inscription)
                <x-table.tr >
                    <x-table.td>
                        {{ $inscription->name }}
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
                        #{{ optional($inscription->sale)->id }}
                    </x-table.td>
                    <x-table.td>
                        {{ $inscription->created_at }}
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
    </div>


</div>
