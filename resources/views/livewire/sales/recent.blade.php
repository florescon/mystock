<div>
    <x-modal wire:model="recentSales" maxWidth="5xl">
        <x-slot name="title">
            {{ __('Recent Sales') }}
        </x-slot>
        <x-slot name="content">
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
            </div>
            <div>
                <x-table>
                    <x-slot name="thead">
                        <x-table.th>
                            ID
                        </x-table.th>
                        <x-table.th sortable multi-column wire:click="sortField('date')" :direction="$sorts['date'] ?? null">
                            {{ __('Date') }}
                        </x-table.th>
                        <x-table.th sortable multi-column wire:click="sortField('customer_id')" :direction="$sorts['customer_id'] ?? null">
                            {{ __('Customer') }}
                        </x-table.th>
                        <x-table.th sortable multi-column wire:click="sortField('payment_status')" :direction="$sorts['payment_status'] ?? null">
                            {{ __('Payment status') }}
                        </x-table.th>
                        <x-table.th sortable multi-column wire:click="sortField('due_amount')" :direction="$sorts['due_amount'] ?? null">
                            {{ __('Due Amount') }}
                        </x-table.th>
                        <x-table.th sortable multi-column wire:click="sortField('total')" :direction="$sorts['total'] ?? null">
                            {{ __('Total') }}
                        </x-table.th>
                        <x-table.th sortable multi-column wire:click="sortField('status')" :direction="$sorts['status'] ?? null">
                            {{ __('Status') }}
                        </x-table.th>
                        <x-table.th>
                            {{ __('Actions') }}
                        </x-table.th>
                    </x-slot>

                    <x-table.tbody>
                        @forelse ($sales as $saleRecent)
                            <x-table.tr >
                                <x-table.td class="pr-0">
                                    #{{  $saleRecent->id }} _ {{ $saleRecent->reference }}
                                </x-table.td>
                                <x-table.td>
                                    {{ $saleRecent->date }}
                                </x-table.td>
                                <x-table.td>
                                    @if ($saleRecent->customer)
                                        <a href="{{ route('customer.details', $saleRecent->customer->uuid) }}"
                                            class="text-indigo-500 hover:text-indigo-600">
                                            {{ $saleRecent->customer->name }}
                                        </a>
                                    @else
                                        {{ $saleRecent->customer?->name }}
                                    @endif
                                </x-table.td>
                                <x-table.td>
                                    @php
                                        $type = $saleRecent->payment_status->getBadgeType();
                                    @endphp
                                    <x-badge :type="$type">{{ __($saleRecent->payment_status->getName()) }}</x-badge>
                                </x-table.td>
                                <x-table.td>
                                    {{ format_currency($saleRecent->due_amount) }}
                                </x-table.td>

                                <x-table.td>
                                    {{ format_currency($saleRecent->total_amount_with_tax) }}
                                </x-table.td>

                                <x-table.td>
                                    @php
                                        $badgeType = $saleRecent->status->getBadgeType();
                                    @endphp

                                    <x-badge :type="$badgeType">{{ __($saleRecent->status->getName()) }}</x-badge>
                                </x-table.td>
                                <x-table.td>
                                    <div class="flex justify-start space-x-2">
                                        <x-dropdown align="right" width="56">
                                            <x-slot name="trigger" class="inline-flex">
                                                <x-button primary type="button" class="text-white flex items-center">
                                                    <i class="fas fa-angle-double-down"></i>
                                                </x-button>
                                            </x-slot>

                                            <x-slot name="content">
                                                <x-dropdown-link wire:click="showModal({{ $saleRecent->id }})"
                                                    wire:loading.attr="disabled">
                                                    <i class="fas fa-eye"></i>
                                                    {{ __('View') }}
                                                </x-dropdown-link>

                                                <x-dropdown-link target="_blank"
                                                    href="{{ route('sales.pos.pdf', $saleRecent->id) }}"
                                                    wire:loading.attr="disabled">
                                                    <i class="fas fa-print"></i>
                                                    {{ __('Print') }}
                                                </x-dropdown-link>
                                            </x-slot>
                                        </x-dropdown>
                                    </div>
                                </x-table.td>
                            </x-table.tr>
                        @empty
                            <x-table.tr>
                                <x-table.td colspan="9">
                                    <div class="flex justify-center items-center">
                                        <span
                                            class="text-gray-400 dark:text-gray-300">{{ __('No results found') }}</span>
                                    </div>
                                </x-table.td>
                            </x-table.tr>
                        @endforelse
                    </x-table.tbody>
                </x-table>
            </div>

            <div class="px-6 py-3">
                {{ $sales->links() }}
            </div>

            <x-modal wire:model="showModal">
                <x-slot name="title">
                    {{ __('Show Sale') }} - {{ __('Reference') }}: <strong>{{ $sale?->reference }}</strong>
                </x-slot>

                <x-slot name="content">
                    <div class="px-4 mx-auto">
                        <div class="flex flex-row">
                            <div class="w-full">
                                <div class="p-2 flex flex-wrap items-center">
                                    @if ($sale != null)
                                        <x-button secondary class="d-print-none" target="_blank"
                                            wire:loading.attr="disabled" href="{{ route('sales.pdf', $sale->id) }}"
                                            class="ml-auto">
                                            <i class="fas fa-print"></i> {{ __('Print') }}
                                        </x-button>
                                    @endif
                                </div>
                                <div class="p-4">
                                    <div class="flex flex-row mb-4">

                                        <div class="md:w-1/2 mb-3 md:mb-0">
                                            <h5 class="mb-2 border-bottom pb-2">{{ __('Customer Info') }}:</h5>
                                            <div><strong>{{ $sale?->customer?->name }}</strong></div>
                                            <div>{{ $sale?->customer?->address }}</div>
                                            <div>{{ __('Email') }}: {{ $sale?->customer?->email }}</div>
                                            <div>{{ __('Phone') }}: {{ $sale?->customer?->phone }}</div>
                                        </div>

                                        <div class="md:w-1/2 mb-3 md:mb-0">
                                            <h5 class="mb-2 border-bottom pb-2">{{ __('Invoice Info') }}:</h5>
                                            <div>{{ __('Invoice') }}:
                                                <strong>{{ $sale?->reference }}</strong>
                                            </div>
                                            <div>{{ __('Date') }}:
                                                {{ format_date($sale?->date) }}
                                            </div>
                                            <div>
                                                {{ __('Status') }} :
                                                @php
                                                    $badgeType = $sale?->status->getBadgeType();
                                                @endphp

                                                <x-badge :type="$badgeType">{{ __($sale?->status->getName()) }}</x-badge>
                                            </div>
                                            <div>
                                                {{ __('Payment Status') }} :
                                                @php
                                                    $type = $sale?->payment_status->getBadgeType();
                                                @endphp
                                                <x-badge
                                                    :type="$type">{{ __($sale?->payment_status->getName()) }}</x-badge>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="">
                                        <x-table>
                                            <x-slot name="thead">
                                                <x-table.th>{{ __('Concept') }}</x-table.th>
                                                <x-table.th>{{ __('Quantity') }}</x-table.th>
                                                <x-table.th>{{ __('Unit Price') }}</x-table.th>
                                                <x-table.th>{{ __('Discount') }}</x-table.th>
                                                <x-table.th>{{ __('Subtotal') }}</x-table.th>
                                            </x-slot>

                                            <x-table.tbody>
                                                @if ($sale != null)
                                                    @foreach ($sale->saleDetailsTax as $saleDetailsTax)
                                                        <x-table.tr>
                                                            <x-table.td>
                                                                Comisión Uso Tarjeta
                                                            </x-table.td>

                                                            <x-table.td>
                                                                1
                                                            </x-table.td>

                                                            <x-table.td>
                                                                {{ format_currency($saleDetailsTax->tax) }}
                                                            </x-table.td>

                                                            <x-table.td>
                                                                --
                                                            </x-table.td>

                                                            <x-table.td>
                                                                {{ format_currency($saleDetailsTax->tax) }}
                                                            </x-table.td>
                                                        </x-table.tr>
                                                    @endforeach
                                                    @foreach ($sale->saleDetails as $item)
                                                        <x-table.tr>
                                                            <x-table.td>
                                                                {{ $item->name }} <br>
                                                                <x-badge type="success">
                                                                    {{ $item->code }}
                                                                </x-badge>
                                                            </x-table.td>

                                                            <x-table.td>
                                                                {{ $item->quantity }}
                                                            </x-table.td>

                                                            <x-table.td>
                                                                {{ format_currency($item->unit_price) }}
                                                            </x-table.td>

                                                            <x-table.td>
                                                                ${{ $item->product_discount_amount * $item->quantity }}
                                                            </x-table.td>

                                                            <x-table.td>
                                                                {{ format_currency($item->sub_total) }}
                                                            </x-table.td>
                                                        </x-table.tr>
                                                    @endforeach
                                                    @foreach ($sale->saleDetailsService as $itemService)
                                                        <x-table.tr>
                                                            <x-table.td>
                                                                {{ $itemService->name }} <br>
                                                                <x-badge type="success">
                                                                    {{ $itemService->code }}
                                                                </x-badge>
                                                            </x-table.td>
                                                            <x-table.td>
                                                                {{ $itemService->quantity }}
                                                            </x-table.td>

                                                            <x-table.td>
                                                                {{ format_currency($itemService->unit_price) }}
                                                            </x-table.td>

                                                            <x-table.td>
                                                                ${{ $itemService->product_discount_amount * $itemService->quantity }}
                                                            </x-table.td>

                                                            <x-table.td>
                                                                {{ format_currency($itemService->sub_total) }}
                                                            </x-table.td>
                                                        </x-table.tr>
                                                    @endforeach
                                                @endif
                                            </x-table.tbody>
                                        </x-table>
                                    </div>
                                    <div class="w-full px-4 mb-4">
                                        <table class="table">
                                            <tbody>
                                                <tr>
                                                    <td class="left"><strong>{{ __('Discount') }}
                                                            ({{ $sale?->discount_percentage }}%)</strong>
                                                    </td>
                                                    <td class="right">
                                                        {{ format_currency($sale?->discount_amount) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="left"><strong>{{ __('Tax') }}
                                                            ({{ $sale?->tax_percentage }}%)</strong></td>
                                                    <td class="right">
                                                        {{ format_currency($sale?->tax_amount) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="left">
                                                        <strong>{{ __('Shipping') }}</strong>
                                                    </td>
                                                    <td class="right">
                                                        {{ format_currency($sale?->shipping_amount) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="left">
                                                        <strong>{{ __('Grand Total') }}</strong>
                                                    </td>
                                                    <td class="right">
                                                        <strong>{{ format_currency($sale?->total_amount) }}</strong>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-slot>
            </x-modal>
        </x-slot>
    </x-modal>
    <div class="absolute inset-x-0 -top-40 -z-10 transform-gpu overflow-hidden blur-3xl sm:-top-50" aria-hidden="true">
        <div class="relative left-[calc(50%-11rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#00FFFF] to-[#FFA07A] opacity-10 sm:left-[calc(50%-30rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>

        <div class="absolute inset-x-0 top-[calc(100%-13rem)] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[calc(100%-30rem)]" aria-hidden="true">
          <div class="relative left-[calc(50%+3rem)] aspect-[1155/678] w-[36.125rem] -translate-x-1/2 bg-gradient-to-tr from-[#00FF00] to-[#9089fc] opacity-50 sm:left-[calc(60%+36rem)] sm:w-[72.1875rem]" style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)"></div>
        </div>
    </div>
</div>
