<div>
    <div class="relative mb-4">
        <div class="w-full rounded-lg">
            <x-input wire:model.debounce.500ms="searchQuery" autofocus
                x-on:keydown.escape="searchQuery"
                placeholder="{{ __('Search with names and codes, or reference') }}" />
        </div>
        @if (!empty($searchQuery))
            <div class="absolute top-0 left-0 w-full mt-12 bg-white rounded-md shadow-xl overflow-y-auto max-h-52 z-50">
                <ul>
                    @if (!is_null($this->product) && $this->product->count())
                        <li class="flex items-center text-left px-4 py-3 border-b border-gray-100">
                            <x-chips label="{{ __('Products') }}" class="mr-2" shade="dark" color="red" />
                            <div class="flex space-x-4">
                                @foreach ($this->product as $item)
                                    <p class="font-semibold text-gray-700">
                                        {{ __('Name') }} <br>
                                        {{ $item->name }}
                                    </p>
                                    <p class="font-semibold text-gray-700">
                                        {{ __('Price') }} <br>
                                        {{ format_currency($item->average_price) }}
                                    </p>
                                    <p class="font-semibold text-gray-700">
                                        {{ __('Cost') }} <br>
                                        {{ format_currency($item->average_cost) }}
                                    </p>
                                    <p class="font-semibold text-gray-700">
                                        {{ __('Quantity') }} <br>
                                        {{ $item->total_quantity }}
                                    </p>
                                @endforeach
                            </div>
                        </li>
                    @endif
                    @if (!is_null($this->customer) && $this->customer->count())
                        <li class="flex items-center text-left px-4 py-3 border-b border-gray-100">
                            <x-chips label="{{ __('Customers') }}" class="mr-2" shade="dark" color="yellow" />
                            <div class="mx-4 space-y-2">
                                @foreach ($this->customer as $item)
                                    <p class="font-semibold text-gray-700">
                                        {{ __('Name') }} <br>
                                        {{ $item->name }}
                                    </p>
                                    <p class="font-semibold text-gray-700">
                                        {{ __('Phone') }} <br>
                                        {{ $item->phone }}
                                    </p>
                                    <p class="font-semibold text-gray-700">
                                        {{ __('Address') }} <br>
                                        {{ $item->address }}
                                    </p>
                                    <x-button info href="{{ route('customer.details', $item->uuid) }}">
                                        <i class="fas fa-book"></i>
                                        {{ __('Details') }}
                                    </x-button>
                                @endforeach
                            </div>
                        </li>
                    @endif
                    @if (!is_null($this->supplier) && $this->supplier->count())
                        <li class="flex items-center text-left px-4 py-3 border-b border-gray-100">
                            <x-chips label="{{ __('Suppliers') }}" class="mr-2" shade="dark" color="green" />
                            <div class="mx-4 space-y-2">
                                @foreach ($this->supplier as $item)
                                    <p class="font-semibold text-gray-700">
                                        {{ __('Name') }} <br>
                                        {{ $item->name }}
                                    </p>
                                    <p class="font-semibold text-gray-700">
                                        {{ __('Phone') }} <br>
                                        {{ $item->phone }}
                                    </p>
                                    <x-button info href="{{ route('supplier.details', $item->uuid) }}">
                                        <i class="fas fa-book"></i>
                                        {{ __('Details') }}
                                    </x-button>
                                @endforeach
                            </div>
                        </li>
                    @endif
                    @if (!is_null($this->sale) && $this->sale->count())
                        <li class="flex items-center text-left px-4 py-3 border-b border-gray-100">
                            <x-chips label="{{ __('Sales') }}" class="mr-2" shade="dark" color="blue" />
                            <div class="mx-4">
                                @foreach ($this->sale as $item)
                                    <p class="font-semibold text-gray-700">{{ __('Date') }} :{{ $item->date }}
                                    </p>
                                    <p class="font-semibold text-gray-700">{{ __('Customer name') }}
                                        : {{ $item->customer->name }}
                                    </p>
                                    <p class="font-semibold text-gray-700">{{ __('Reference') }}
                                        : {{ $item->reference }}</p>
                                    <p class="font-semibold text-gray-700">{{ __('Total amount') }}
                                        : {{ format_currency($item->total_amount) }}</p>
                                    <p class="font-semibold text-gray-700">{{ __('Due amount') }}
                                        : {{ format_currency($item->due_amount) }}</p>
                                @endforeach
                            </div>
                        </li>
                    @endif
                    @if (!is_null($this->purchase) && $this->purchase->count())
                        <li class="flex items-center text-left px-4 py-3 border-b border-gray-100">
                            <x-chips label="{{ __('Purchases') }}" class="mr-2" shade="dark" color="cyan" />
                            <div class="mx-4">
                                @foreach ($this->purchase as $item)
                                    <p class="font-semibold text-gray-700">{{ __('Date') }} <br>
                                        {{ $item->date }}
                                    </p>
                                    <p class="font-semibold text-gray-700">{{ __('Supplier name') }}
                                        <br>
                                        {{ $item->supplier->name }}
                                    </p>
                                    <p class="font-semibold text-gray-700">{{ __('Reference') }}
                                        <br>
                                        {{ $item->reference }}
                                    </p>
                                    <p class="font-semibold text-gray-700">{{ __('Total amount') }}
                                        <br>
                                        {{ format_currency($item->total_amount) }}
                                    </p>
                                    <p class="font-semibold text-gray-700">{{ __('Due amount') }}
                                        <br>
                                        {{ format_currency($item->due_amount) }}
                                    </p>
                                @endforeach
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        @endif
    </div>
    <div class="flex flex-wrap my-2 gap-2">
        <x-chips label="{{ __('Products') }}" shade="dark" color="red" />

        <x-chips label="{{ __('Customers') }}" shade="dark" color="yellow" />

        <x-chips label="{{ __('Suppliers') }}" shade="dark" color="green" />

        <x-chips label="{{ __('Sales') }}" shade="dark" color="blue" />

        <x-chips label="{{ __('Purchases') }}" shade="dark" color="cyan" />
    </div>
</div>
