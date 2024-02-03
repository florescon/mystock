<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap my-2">
            <select wire:model="perPage"
                class="w-20 block p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>
            @if ($selected)
                <x-button danger type="button" wire:click="deleteSelected" class="ml-3">
                    <i class="fas fa-trash"></i>
                </x-button>
            @endif
            @if ($this->selectedCount)
                <p class="text-sm leading-5 ml-3">
                    <span class="font-medium">
                        {{ $this->selectedCount }}
                    </span>
                    {{ __('Entries selected') }}
                </p>
            @endif
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
            <x-table.th sortable wire:click="sortBy('id')" :direction="$sorts['id'] ?? null">
                {{ __('ID') }}
            </x-table.th>
            <x-table.th>
                {{ __('Created By') }}
            </x-table.th>
            <x-table.th>
                {{ __('Initial') }}
            </x-table.th>
            <x-table.th>
                {{ __('Cash') }}
            </x-table.th>
            <x-table.th>
                {{ __('Another Payment Methods') }}
            </x-table.th>
            <x-table.th>
                {{ __('Created At') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>

        <x-table.tbody>
            @forelse ($cashes as $cash)
                <x-table.tr >
                    <x-table.td>
                        #{{ $cash->id }}
                    </x-table.td>
                    <x-table.td>
                        {{ $cash->user->name }}
                    </x-table.td>
                    <x-table.td>
                        ${{ $cash->initial }}
                    </x-table.td>
                    <x-table.td>
                        ${{  $cash->total_cash + $cash->total_incomes - $cash->total_expenses }}
                    </x-table.td>
                    <x-table.td>
                        ${{ $cash->total_other }}
                    </x-table.td>
                    <x-table.td>
                        {{ $cash->created_at }}
                    </x-table.td>
                    <x-table.td>
                        @if($cash)
                            @if($cash->is_processed)
                                <x-button target="_blank" primary class="d-print-none"
                                    href="{{ route('cash-history-print-short.index', $cash->id) }}">
                                    {{ __('Print') }}
                                </x-button>
                                <x-button target="_blank" secondary class="d-print-none"
                                    href="{{ route('cash-history-print.index', $cash->id) }}">
                                    {{ __('Print Extended') }}
                                </x-button>
                            @else
                                <p class="text-base text-blue-600/75">@lang('Pending to process') ...</p>
                            @endif
                        @endif

                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="5">
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
        {{ $cashes->links() }}
    </div>

    <x-modal wire:model="importModal">
        <x-slot name="title">
            <div class="flex justify-between items-center">
                {{ __('Import Excel') }}
                <x-button primary wire:click="downloadSample" type="button">
                    {{ __('Download Sample') }}
                </x-button>
            </div>
        </x-slot>

        <x-slot name="content">
            <form wire:submit.prevent="import">
                <div class="mb-4">

                    <div class="w-full px-3">
                        <x-label for="import" :value="__('Import')" />
                        <x-input id="import" class="block mt-1 w-full" type="file" name="import"
                            wire:model.defer="import_file" />
                        <x-input-error :messages="$errors->get('import')" for="import" class="mt-2" />
                    </div>

                    <div class="w-full px-3">
                        <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                            {{ __('Import') }}
                        </x-button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>

</div>
