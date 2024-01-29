<div>
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
                <x-input wire:model.debounce.500ms="search" placeholder="{{ __('Search') }}" autofocus />
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
                {{ __('Quantity') }}
            </x-table.th>
            <x-table.th>
                {{ __('Discount') }}
            </x-table.th>
            <x-table.th>
                {{ __('Subtotal') }}
            </x-table.th>
            <x-table.th>
                {{ __('Sale') }}
            </x-table.th>
            <x-table.th>
                {{ __('Date') }}
            </x-table.th>
        </x-slot>

        <x-table.tbody>
            @forelse ($inscriptions as $free)
                <x-table.tr wire:loading.class.delay="opacity-50">
                    <x-table.td>
                        {{ $free->name }}
                    </x-table.td>
                    <x-table.td>
                        {{ optional($free->customer)->name }}
                    </x-table.td>
                    <x-table.td>
                        {{ $free->quantity }}
                    </x-table.td>
                    <x-table.td>
                        $ {{ $free->product_discount_amount * $free->quantity }}
                    </x-table.td>
                    <x-table.td>
                        $ <p class="text-blue-600/100 inline-block">{{ $free->sub_total }}</p>
                    </x-table.td>
                    <x-table.td>
                        #{{ optional($free->sale)->id }}
                    </x-table.td>
                    <x-table.td>
                        {{ $free->created_at }}
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="7">
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
