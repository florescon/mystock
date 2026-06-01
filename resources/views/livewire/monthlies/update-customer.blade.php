<div>
    <!-- Edit Modal -->
    <x-modal wire:model="editCustomer">
        <x-slot name="title">
            {{ __('Edit Customer') }} - {{ $monthlie ? optional($monthlie->customer)->name : '' }}
        </x-slot>

        <x-slot name="content">
            <!-- Validation Errors -->
            <div class="w-full text-center border-dashed border-4 border-indigo-600 py-9">

                  <div class="px-4 sm:px-0  border-solid border-4 border-indigo-600 mx-6 mb-4">
                    <h3 class="text-base font-semibold leading-7 text-gray-900 pt-2">Actual Horario</h3>
                    <h3 class="text-base font-semibold leading-7 text-blue-500 pt-2">{{ $monthlie ? $monthlie->name : '' }}</h3>
                    <p class="mt-1 text-sm  text-red-500 pb-2">{{ $monthlie ? optional($monthlie->customer)->name : '' }}</p>
                  </div>
                @if($monthlie->with_days)
                    {!! implode('<br> ', $monthlie->with_days) !!}
                @endif
            </div>

            <x-validation-errors class="mb-4" :errors="$errors" />

            @if($selectedDays || $quantitySelectDays)
                <button class=" w-full bg-red-100 hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 mt-3 border border-gray-400 rounded shadow ml-1" wire:click.prevent="resetSchedule">
                    Limpiar
                </button>
            @else
                <div class="py-7">
                </div>
            @endif

            <form wire:submit.prevent="updateCustomerMonthlie">

                <div class="flex items-center justify-center rounded-md mb-8 py-4">
                    <div class="flex items-center max-w-md mx-auto bg-white rounded-lg mb-8" >
                        <div class="w-full mb-8">

                        <livewire:components.select-customer
                            name="select_customer_id"
                            placeholder="{{ __('Choose a Customer') }}"
                            :value="request('select_customer_id')"
                            :searchable="true"
                            :key="'remove-'.$monthlie->id"
                        />

                        </div>
                    </div>                                 
                </div>

                <div class="w-full px-3 py-2 mb-4">
                    <x-button primary type="submit" class="w-full text-center" wire:loading.attr="disabled">
                        {{ __('Update') }}
                    </x-button>
                </div>

            </form>
        </x-slot>
    </x-modal>
    <!-- End Edit Modal -->
</div>
