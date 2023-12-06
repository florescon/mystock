<div>
    <x-modal wire:model="showPayments">
        <x-slot name="title">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Payments') }}
            </h2>
            <div class="flex justify-end">
                @if ($sale?->due_amount > 0)
                    <x-button 
                        x-on:click="$wire.set('showPayments', false)"
                        wire:click="$emit('paymentModal', {{ $sale->id }})"
                        primary type="button">
                        {{ __('Add Payment') }}
                    </x-button>
                @endif
            </div>
        </x-slot>
        <x-slot name="content">
            @if ($sale?->due_amount > 0)
                <div class="p-8 mt-10">
                    <div class="bg-white w-1/2 mx-auto p-4 rounded-md shadow-lg bg-gray-50 text-center">
                        <p class="text-gray-700 text-left mb-4 text-center">@lang('Due')</p>
                        <h1 class="text-2xl font-bold text-indigo-500 mb-4">{{ format_currency($sale?->due_amount) }}</h1>
                    </div>
                </div>
            @endif
            <x-table>
                <x-slot name="thead">
                    <x-table.th>{{ __('Date') }}</x-table.th>
                    <x-table.th>{{ __('Amount') }}</x-table.th>
                    <x-table.th>{{ __('Payment Method') }}</x-table.th>
                </x-slot>
                <x-table.tbody>

                    @forelse ($salepayments as $salepayment)
                        <x-table.tr>
                            <x-table.td>{{ $salepayment->created_at }}</x-table.td>
                            <x-table.td>
                                {{ format_currency($salepayment->amount) }}
                            </x-table.td>
                            <x-table.td>{{ __($salepayment->payment_method) }}</x-table.td>
                        </x-table.tr>
                    @empty
                        <x-table.tr>
                            <x-table.td colspan="4">{{ __('No data found') }}</x-table.td>
                        </x-table.tr>
                    @endforelse
                </x-table.tbody>
            </x-table>

            <div class="mt-4">
                {{-- {{ $sale->salepayments->links() }} --}}
            </div>

        </x-slot>
    </x-modal>

</div>
