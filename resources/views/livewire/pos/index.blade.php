<div>
    <div class="w-full px-2" dir="ltr">
        <x-validation-errors class="mb-4" :errors="$errors" />

            {{-- Customer: @json($customer_id) - --}}
            {{-- Total: @json('$'.$total_amount) --}}
            {{-- @json($cart_items) --}}
        <div class="flex gap-4">

            <div class="w-full relative inline-flex">
                <select required id="warehouse_id" name="warehouse_id" wire:model="warehouse_id"
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                    {{-- @if (settings()->default_warehouse_id == true)
                        <option value="{{ $default_warehouse?->id }}" selected>{{ $default_warehouse?->name }}
                        </option>
                    @endif --}}
                    {{-- <option value="">{{ __('Select warehouse') }}</option> --}}
                    @foreach ($this->warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="w-full relative inline-flex">
                <select required id="customer_id" name="customer_id" wire:model="customer_id"
                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                    <option value="">{{ __('Select Customer') }}</option>
                    @foreach ($this->customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        @if((int) $customer_id)
            @livewire('customers.alert-inscription', ['customer_id' => $customer_id])
        @endif

        <livewire:product-cart :cartInstance="'sale'" />

    </div>

    <div class="mb-4 d-flex justify-center flex-wrap py-3">
        <x-button danger type="button" wire:click="resetCart" wire:loading.attr="disabled" class="ml-2 font-bold">
            {{ __('Reset') }}
        </x-button>
        <button
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 bg-green-500 hover:bg-green-700"
            type="submit" wire:click="proceed" wire:loading.attr="disabled" {{ (($total_amount == 0) || !$customer_id) ? 'disabled' : '' }}>
            {{ __('Proceed') }}
        </button>
    </div>

    <x-modal wire:model="checkoutModal">
        <x-slot name="title">
            {{ __('Checkout') }}
        </x-slot>

        <x-slot name="content">

            {{-- @json(Cart::instance('sale')->content()) --}}

            <form id="checkout-form" wire:submit.prevent="store" class="py-5">
                <div class="flex flex-wrap">
                    <div class="w-1/2 px-2">
                        <div class="flex flex-wrap -mx-2 mb-3">
                            <div class="w-full px-2">
                                <x-label for="total_amount" :value="__('Total Amount')" required />
                                <p class="text-center">{{ $total_amount }}</p>
                            </div>
                            <div class="w-full px-2">
                                <x-label for="paid_amount" :value="__('Paid Amount')" required />
                                <input id="paid_amount" type="text" wire:model="paid_amount"
                                    class="block w-full text-center shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"
                                    name="paid_amount" required>
                                <x-input-error :messages="$errors->get('paid_amount')" for="paid_amount" class="mt-2" />
                            </div>
                            <div class="w-full px-2">
                                <x-label for="difference" :value="__('Difference')" required />
                                    @if(($total_amount == $paid_amount) && ($difference == 0))
                                        <p class="text-center text-blue-600">@lang('Covered payment') <i class="fa-solid fa-check-double text-green-500"></i> </p>
                                    @else
                                        <p class="text-center">{{ $difference }}</p>
                                    @endif
                            </div>
                            <div class="w-full px-2">
                                <x-label for="payment_method" :value="__('Payment Method')" required />
                                <select wire:model="payment_method" id="payment_method" required
                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1">
                                    <option value="Cash">{{ __('Cash') }}</option>
                                    <option value="Bank Transfer">{{ __('Bank Transfer') }}</option>
                                    <option value="Card">{{ __('Card') }}</option>
                                    <option value="Other">{{ __('Other') }}</option>
                                </select>
                            </div>
                            <div class="mb-4 w-full px-2">
                                <x-label for="note" :value="__('Note')" />
                                <textarea name="note" id="note" rows="5" wire:model.lazy="note"
                                    class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm border-gray-300 rounded-md mt-1"></textarea>
                                <x-input-error :messages="$errors->get('note')" for="note" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    <div class="w-1/2 px-2">
                        <x-table-responsive>
                            <x-table.tr>
                                <x-table.th>
                                    {{ __('Concepts') }}
                                </x-table.th>
                                <x-table.td>
                                    <span class="badge badge-success">
                                        {{ Cart::instance($cart_instance)->count() }}
                                    </span>
                                </x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>
                                    {{ __('Order Tax') }} ({{ $global_tax }}%)
                                </x-table.th>
                                <x-table.td>
                                    (+) {{ format_currency(Cart::instance($cart_instance)->tax()) }}
                                </x-table.td>
                            </x-table.tr>
                            <x-table.tr>
                                <x-table.th>
                                    {{ __('Discount') }} ({{ $global_discount }}%)
                                </x-table.th>
                                <x-table.td>
                                    (-) {{ format_currency(Cart::instance($cart_instance)->discount()) }}
                                </x-table.td>
                            </x-table.tr>
                            {{-- <x-table.tr>
                                <x-table.th>
                                    {{ __('Shipping') }}
                                </x-table.th>
                                <x-table.td>
                                    (+) {{ format_currency($shipping_amount) }}
                                </x-table.td>
                            </x-table.tr> --}}
                            <x-table.tr>
                                <x-table.th>
                                    {{ __('Grand Total') }}
                                </x-table.th>
                                <x-table.td>
                                    (=) {{ format_currency($total_amount) }}
                                </x-table.td>
                            </x-table.tr>
                        </x-table-responsive>
                    </div>
                </div>
                <div class="float-left pb-4 px-2">
                    <div x-data="{ open : false }" class="w-96 rounded border bg-white p-4 shadow ml-4">
                        <div x-show="!open" class="flex items-center justify-between">
                            <div class="ml-2">¿Realmente desea procesar?</div>
                            <button type="button" class="btn rounded bg-gray-200 px-4 py-2 font-medium hover:bg-gray-300" @click="open = !open">Procesar</button>
                        </div>

                        <!-- container after clicked "EDIT" -->
                        <div x-show="open" class="flex items-center justify-between">
                            <p>
                                Sí, procesar
                            </p>

                            <div class="flex items-center justify-center space-x-2">

                                <x-button primary type="submit" class="mr-3" wire:loading.attr="disabled">{{ __('Submit') }}</x-button>
                                <button type="button" @click="open = false" class="btn rounded bg-gray-200 px-4 py-2 font-medium hover:bg-gray-300">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal>
</div>
