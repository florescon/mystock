<div>
    <x-validation-errors class="mb-4" :errors="$errors" />

    <div>
        <div wire:loading.flex class="absolute top-0 left-0 w-full h-full bg-white bg-opacity-75 z-50">
            <div class="m-auto">
                <x-loading />
            </div>
        </div>
        <x-table>
            
            {{-- @json($check_quantity)<br> --}}
            {{-- Warehouse @json($warehouse_id) --}}
            {{-- <br> --}}
            {{-- @json($cart_items) --}}

            <x-slot name="thead">
                <x-table.th>{{ __('Product') }}</x-table.th>
                <x-table.th>{{ __('Net Unit Price') }}</x-table.th>
                <x-table.th>{{ __('Stock') }}</x-table.th>
                <x-table.th>{{ __('Quantity') }}</x-table.th>
                <x-table.th>{{ __('Sub Total') }}</x-table.th>
                <x-table.th>{{ __('Action') }}</x-table.th>
            </x-slot>
            <x-table.tbody>
                @if ($cart_items->isNotEmpty())
                    @foreach ($cart_items as $cart_item)
                        <x-table.tr>
                            <x-table.td>
                                {{ $cart_item->name }} <br>
                                <span class="font-light text-xs text-gray-600">{{ $cart_item->options->code }}</span>
                                {{-- @include('livewire.includes.product-cart-modal') --}}

                                @livewire('includes.product-cart-modal', ['cart_item' => (array) $cart_item], key('show-modal-' . $cart_item?->rowId))

                            </x-table.td>

                            <x-table.td>
                                {{ format_currency($cart_item->options->unit_price) }}
                                @include('livewire.includes.product-cart-price')
                            </x-table.td>

                            <x-table.td>
                                @if(!$cart_item->options->service_type)
                                    <span
                                        class="badge badge-info">
                                        {{ $cart_item->options->stock . ' ' . $cart_item->options->unit }}
                                    </span>
                                @else
                                    {{ __('No apply') }}
                                @endif
                            </x-table.td>

                            <x-table.td>
                                @if(!$cart_item->options->service_type)
                                    @include('livewire.includes.product-cart-quantity')
                                @else
                                    {{ $cart_item->qty }} - {{ __('Service') }}
                                @endif
                            </x-table.td>

                            <x-table.td>
                                {{ format_currency($cart_item->options->sub_total) }}
                            </x-table.td>

                            <x-table.td>
                                <a href="#" wire:click.prevent="removeItem('{{ $cart_item->rowId }}')">
                                    <i class="bi bi-x-circle font-2xl text-danger"></i>
                                </a>
                            </x-table.td>
                        </x-table.tr>
                        @if($cart_item->options->customer_id)
                            <x-table.tr>
                                <x-table.td class="text-center">
                                    <span class="text-red-500">
                                        <i class="fa-regular fa-circle-up"></i>
                                    </span>
                                </x-table.td>
                                <x-table.td colspan="5" class="text-left">
                                    <span class="text-red-500">
                                        {{ $cart_item->options->customer_name }}
                                    </span>
                                </x-table.td>
                            </x-table.tr>
                        @endif
                        @if($cart_item->options->days)
                            <x-table.tr>
                                <x-table.td class="text-center">
                                    <span class="text-red-500">
                                        <i class="fa-regular fa-circle-up"></i>
                                    </span>
                                </x-table.td>
                                <x-table.td colspan="5" class="text-left">
                                    <span class="text-red-500">
                                        {{ implode(', ', $cart_item->options->days) }}
                                    </span>
                                    @if($cart_item->options->hour)
                                        <span class="text-blue-500">
                                            {{ '['.$cart_item->options->hour.']' }}
                                        </span>
                                    @endif
                                </x-table.td>
                            </x-table.tr>
                        @endif
                    @endforeach
                @else
                    <x-table.tr>
                        <x-table.td colspan="6" class="text-center">
                            <span class="text-red-500">
                                {{ __('Please search & select products!') }}
                            </span>
                        </x-table.td>
                    </x-table.tr>
                @endif
            </x-table.tbody>
        </x-table>
    </div>
    <div class="flex flex-wrap md:justify-end">
        <div class="w-full">
            <div class="w-full py-2">
                <x-table-responsive>
                    @if (settings()->show_order_tax == true)
                    <x-table.tr>
                        <x-table.th>{{ __('Order Tax') }} ({{ $global_tax }}%)</x-table.th>
                        <x-table.td>(+) {{ format_currency(Cart::instance($cart_instance)->tax()) }}</x-table.td>
                    </x-table.tr>
                    @endif
                    @if (settings()->show_discount == true)
                    <x-table.tr>
                        <x-table.th>{{ __('Discount') }} ({{ $global_discount }}%)</x-table.th>
                        <x-table.td>(-) {{ format_currency(Cart::instance($cart_instance)->discount()) }}</x-table.td>
                    </x-table.tr>
                    @endif
                    <x-table.tr>
                        <x-table.th>{{ __('Grand Total') }}</x-table.th>
                        @php
                            $total_with_shipping = Cart::instance($cart_instance)->total() + (float) $shipping_amount;
                        @endphp
                        <x-table.th>
                            (=) {{ format_currency($total_with_shipping) }}
                        </x-table.th>
                    </x-table.tr>
                </x-table-responsive>
            </div>
        </div>
    </div>

    <input type="hidden" name="total_amount" value="{{ $total_with_shipping }}">

    <div class="flex flex-wrap my-2">
        <div class="w-full md:w-1/2 px-2 mb-4 md:mb-0">
            <div class="mb-4">
                <label for="tax_percentage">{{ __('Order Tax (%)') }}</label>
                <x-input wire:model.lazy="global_tax" value="{{ $global_tax }}" />
                <x-input-error :messages="$errors->get('global_tax')" for="global_tax" class="mt-2" />
            </div>
        </div>
        <div class="w-full md:w-1/2 px-2 mb-4 md:mb-0">
            <div class="mb-4">
                <label for="discount_percentage">{{ __('Discount (%)') }}</label>
                <x-input wire:model.lazy="global_discount" value="{{ $global_discount }}" />
                <x-input-error :messages="$errors->get('global_discount')" for="global_discount" class="mt-2" />
            </div>
        </div>
        {{-- <div class="w-full md:w-1/3 px-2 mb-4 md:mb-0">
            <div class="mb-4">
                <label for="shipping_amount">{{ __('Shipping') }}</label>
                <x-input wire:model.lazy="shipping_amount" value="{{ $shipping_amount }}" />
                <x-input-error :messages="$errors->get('shipping_amount')" for="shipping_amount" class="mt-2" />
            </div>
        </div> --}}
    </div>
</div>
