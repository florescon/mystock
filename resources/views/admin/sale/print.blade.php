@section('title', __('Sale Details'))

@extends('layouts.print')

@section('content')
    <div class="container">

        <x-printHeader :customer="$customer" :sale="$sale" :logo="$logo" style="centered" />

        <br>

        <div style="margin-top: 20px;">
            <table style="border-collapse:collapse">
                <thead>
                    <tr class="title">
                        <th class="align-middle">{{ __('Concept') }}</th>
                        <th class="align-middle">{{ __('Net Unit Price') }}</th>
                        <th class="align-middle">{{ __('Quantity') }}</th>
                        <th class="align-middle">{{ __('Discount') }}</th>
                        <th class="align-middle">{{ __('Tax') }}</th>
                        <th class="align-middle">{{ __('Sub Total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php($totalsaleDetailsTax = 0)
                    @foreach ($sale->saleDetailsTax as $saleDetailsTax)
                        <tr>
                            <td class="align-middle">
                                Comisión Uso Tarjeta
                            </td>

                            <td class="align-middle">{{ format_currency($saleDetailsTax->tax) }}</td>

                            <td class="align-middle">
                                1
                            </td>

                            <td class="align-middle">
                                --
                            </td>

                            <td class="align-middle">
                                --
                            </td>

                            <td class="align-middle">
                                {{ format_currency($saleDetailsTax->tax) }}
                            </td>
                        </tr>
                        @php($totalsaleDetailsTax += $saleDetailsTax->tax)
                    @endforeach

                    @foreach ($sale->saleDetails as $item)
                        <tr>
                            <td class="align-middle">
                                {{ $item->name }} <br>
                                <span class="badge badge-success">
                                    {{ $item->code }}
                                </span>
                            </td>

                            <td class="align-middle">{{ format_currency($item->unit_price) }}</td>

                            <td class="align-middle">
                                {{ $item->quantity }}
                            </td>

                            <td class="align-middle">
                                {{ format_currency($item->product_discount_amount * $item->quantity) }}
                            </td>

                            <td class="align-middle">
                                {{ format_currency($item->product_tax_amount) }}
                            </td>

                            <td class="align-middle">
                                {{ format_currency($item->sub_total) }}
                            </td>
                        </tr>
                    @endforeach
                    @foreach ($sale->saleDetailsService as $itemService)
                        <tr>
                            <td class="align-middle">
                                {{ $itemService->name }} <br>
                                <span class="badge badge-success">
                                    {{ $itemService->code }}
                                </span>
                            </td>

                            <td class="align-middle">{{ format_currency($itemService->unit_price) }}</td>

                            <td class="align-middle">
                                {{ $itemService->quantity }}
                            </td>

                            <td class="align-middle">
                                {{ format_currency($itemService->product_discount_amount * $itemService->quantity) }}
                            </td>

                            <td class="align-middle">
                                {{ format_currency($itemService->product_tax_amount) }}
                            </td>

                            <td class="align-middle">
                                {{ format_currency($itemService->sub_total) }}
                            </td>
                        </tr>
                    @endforeach                
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col">
                <table>
                    <tbody>
                        @if ($sale->discount_percentage)
                            <tr>
                                <td class="left"><strong>{{ __('Discount') }}
                                        ({{ $sale->discount_percentage }}%)</strong></td>
                                <td class="right">{{ format_currency($sale->discount_amount) }}</td>
                            </tr>
                        @endif
                        @if ($sale->tax_percentage)
                            <tr>
                                <td class="left"><strong>{{ __('Tax') }}
                                        ({{ $sale->tax_percentage }}%)</strong></td>
                                <td class="right">{{ format_currency($sale->tax_amount) }}</td>
                            </tr>
                        @endif
                        @if (settings()->show_shipping == true)
                            <tr>
                                <td class="left"><strong>{{ __('Shipping') }}</strong></td>
                                <td class="right">{{ format_currency($sale->shipping_amount) }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td class="left"><strong>{{ __('Grand Total') }}</strong></td>
                            <td class="right">
                                <strong>{{ format_currency($sale->total_amount_with_tax) }}</strong>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
