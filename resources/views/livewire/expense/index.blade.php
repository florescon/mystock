<div>
    <div class="flex flex-wrap justify-center">
        <div class="lg:w-1/2 md:w-1/2 sm:w-full flex flex-wrap space-x-2 my-2">
            <select wire:model="perPage"
                class="w-20 block p-3 leading-5 bg-white dark:bg-dark-eval-2 text-gray-700 dark:text-gray-300 rounded border border-gray-300 mb-1 text-sm focus:shadow-outline-blue focus:border-blue-300 mr-3">
                @foreach ($paginationOptions as $value)
                    <option value="{{ $value }}">{{ $value }}</option>
                @endforeach
            </select>

            @if ($selected)
                {{-- <x-button danger type="button" wire:click="deleteSelected" class="ml-3">
                    <i class="fas fa-trash"></i>
                </x-button> --}}
                <x-button success type="button" wire:click="downloadSelected" wire:loading.attr="disabled">
                    {{ __('EXCEL') }}
                </x-button>
                <x-button warning type="button" wire:click="exportSelected" wire:loading.attr="disabled">
                    {{ __('PDF') }}
                </x-button>
                <x-button primary type="button" wire:click="exportSelectedPrint" wire:loading.attr="disabled">
                    {{ __('Print A4') }}
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
        <div class="grid gap-4 grid-cols-2 justify-center mb-2">
            <div class="w-full flex flex-wrap">
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
            <div class="gap-2 inline-flex items-center mx-0 px-2">
                <x-button type="button" primary wire:click="filterByType('day')">{{ __('Today') }}</x-button>
                <x-button type="button" info wire:click="filterByType('month')">{{ __('This Month') }}</x-button>
                <x-button type="button" warning wire:click="filterByType('year')">{{ __('This Year') }}</x-button>
            </div>
        </div>
    </div>
    <x-table>
        <x-slot name="thead">
            <x-table.th>
                <input type="checkbox" wire:model="selectPage" />
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('reference')" :direction="$sorts['reference'] ?? null">
                {{ __('Reference') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('details')" :direction="$sorts['details'] ?? null">
                {{ __('Details') }}
            </x-table.th>
            <x-table.th>
                {{ __('Customer') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('category_id')" :direction="$sorts['category_id'] ?? null">
                {{ __('Expense Category') }}
            </x-table.th>
            <x-table.th>
                {{ __('Created By') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('date')" :direction="$sorts['date'] ?? null">
                {{ __('Date') }}
            </x-table.th>
            <x-table.th sortable wire:click="sortBy('amount')" :direction="$sorts['amount'] ?? null">
                {{ __('Amount') }}
            </x-table.th>
            <x-table.th>
                {{ __('Actions') }}
            </x-table.th>
        </x-slot>

        <x-table.tbody>
            @forelse ($expenses as $expense)
                <x-table.tr  wire:key="row-{{ $expense->id }}">
                    <x-table.td class="pr-0">
                        <input wire:model="selected" type="checkbox" value="{{ $expense->id }}" />
                    </x-table.td>
                    <x-table.td>
                        <button type="button" wire:click="showModal({{ $expense->id }})">
                            {{ $expense->reference }}
                        </button>
                    </x-table.td>
                    <x-table.td>
                        {{ $expense->details ? Str::limit($expense->details, 28, '...') : '--' }}
                    </x-table.td>
                    <x-table.td>
                        {{ $expense->customer_id ? Str::limit(optional($expense->customer)->name, 15, '...') : '--' }}
                    </x-table.td>
                    <x-table.td>
                        @if($expense->category_id)
                            <x-badge type="info">
                                <small>{{ $expense?->category?->name ?? '' }}</small>
                            </x-badge>
                        @endif
                    </x-table.td>
                    <x-table.td>
                        {{ $expense->user_id ? Str::limit(optional($expense->user)->name, 7, '...') : '--' }}
                    </x-table.td>
                    <x-table.td>
                        {{ $expense->date }}
                    </x-table.td>
                    <x-table.td>
                        {{ format_currency($expense->amount) }}
                    </x-table.td>
                    <x-table.td>
                        <div class="flex justify-start space-x-2">
                            <a type="button" class="bg-red-800 border border-transparent text-white hover:bg-red-900 focus:ring-red-900 active:bg-red-900 focus:outline-none focus:border-red-900 inline-flex items-center px-2 rounded-md font-semibold text-xs uppercase tracking-widest disabled:opacity-25 transition ease-in-out duration-150 mr-3" target="_blank" href="{{ route('finance.print', $expense->id) }}">
                                &nbsp; <i class="fas fa-print"></i> &nbsp;
                            </a>

                            <x-button info wire:click="showModal({{ $expense->id }})" type="button"
                                wire:loading.attr="disabled">
                                <i class="fas fa-eye"></i>
                            </x-button>
                            <x-button primary wire:click="$emit('editModal',{{ $expense->id }})" type="button"
                                wire:loading.attr="disabled">
                                <i class="fas fa-edit"></i>
                            </x-button>
                            <x-button danger wire:click="$emit('deleteModal', {{ $expense->id }})" type="button"
                                wire:loading.attr="disabled">
                                <i class="fas fa-trash"></i>
                            </x-button>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="7">
                        <div class="flex justify-center items-center space-x-2">
                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"
                                    clip-rule="evenodd"></path>
                                <path fill-rule="evenodd"
                                    d="M10 4a1 1 0 100 2 1 1 0 000-2zm0 8a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span
                                class="font-medium py-8 text-gray-400 text-xl">{{ __('No expenses found...') }}</span>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @endforelse
        </x-table.tbody>
    </x-table>

    <div class="p-4">
        <div class="pt-3">
            {{ $expenses->links() }}
        </div>
    </div>
    
    @livewire('expense.edit', ['expense' => $expense])

    <livewire:expense.create />

    <x-modal wire:model="showModal">
        <x-slot name="title">
            {{ __('Expense Details') }}
        </x-slot>

        <x-slot name="content">
            <div class="w-full pb-5">
                <div class="flex flex-wrap">
                    <div class="lg:w-1/2 sm:w-full px-2">
                        <x-label for="category_id" :value="__('Expense Category')" />
                        {{ $this->expense?->category?->name }}
                    </div>
                    <div class="lg:w-1/2 sm:w-full px-2">
                        <x-label for="category_id" :value="__('Warehouse')" />
                        {{ $this->expense?->warehouse?->name }}
                    </div>
                    <div class="lg:w-1/2 sm:w-full px-2">
                        <x-label for="date" :value="__('Entry Date')" />
                        {{ $this->expense?->date }}
                    </div>
                    <div class="lg:w-1/2 sm:w-full px-2">
                        <x-label for="reference" :value="__('Reference')" />
                        {{ $this->expense?->reference }}
                    </div>
                    <div class="lg:w-1/2 sm:w-full px-2">
                        <x-label for="amount" :value="__('Amount')" />
                        {{ $this->expense?->amount }}
                    </div>
                    <div class="lg:w-1/2 sm:w-full px-2">
                        <x-label for="customer_id" :value="__('Customer')" />
                        {{ $this->expense?->customer?->name }}
                    </div>

                    <div class="lg:w-1/2 sm:w-full px-2">
                        <x-label for="created_at" :value="__('Created At')" />
                        {{ $this->expense?->created_at }}
                    </div>
                    <div class="lg:w-1/2 sm:w-full px-2">
                        <x-label for="updated_at" :value="__('Updated At')" />
                        {{ $this->expense?->updated_at }}
                    </div>
                    <div class="lg:w-1/2 sm:w-full px-2">
                        <x-label for="user_id" :value="__('Created By')" />
                        {{ $this->expense?->user?->name }}
                    </div>

                    <div class="lg:w-full sm:w-full px-2">
                        <x-label for="details" :value="__('Description')" />
                        {{ $this->expense?->details }}
                    </div>
                </div>
            </div>
        </x-slot>
    </x-modal>

    @push('scripts')
        <script>
            document.addEventListener('livewire:load', function() {
                window.livewire.on('deleteModal', expenseId => {
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
                            window.livewire.emit('delete', expenseId)
                        }
                    })
                })
            })
        </script>
    @endpush

</div>
