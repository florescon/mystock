<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> --}}

    <title>{{ __('Sale') }} : {{ $sale->reference }}</title>

    <style>
        @font-face {
            font-family: 'Cairo';
            src: url('./fonts/cairo.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        * {
            font-family: 'Cairo' !important;
        }

        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-size: 13px;
            line-height: 15px;
            height: 100%;
            -webkit-font-smoothing: antialiased;
        }

        div,
        p,
        a,
        li,
        td {
            -webkit-text-size-adjust: none;
        }


        p {
            padding: 0 !important;
            margin-top: 0 !important;
            margin-right: 0 !important;
            margin-bottom: 0 !important;
            margin- left: 0 !important;
            font-size: 11px;
            line-height: 13px;
        }

        .title {
            background: #EEE;
        }

        td,
        th,
        tr {
            border-collapse: collapse;
            padding: 5px 0;
        }

        tr {
            border-bottom: 1px dashed #ddd;
            border-top: 1px dashed #ddd;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            padding-top: 4px;
        }

        tfoot tr th:first-child {
            text-align: left;
        }

        .centered {
            text-align: center;
            align-content: center;
        }

        small {
            font-size: 11px;
        }

        @page {
            header: page-header;
            footer: page-footer;
        }

        @media print {
            * {
                font-size: 11px;
                line-height: 20px;
            }

            .hidden-print {
                display: none !important;
            }

            tbody::after {
                content: '';
                display: block;
            }
        }
    </style>
</head>

<body>
    <div>
        <div name="page-header" style="margin-bottom: 10px;">
            <div class="centered">
                <img src="{{ public_path('images/logo.png') }}" alt="" width="80"/>
                <h2 style="margin-bottom: 5px;font-size: 16px;">{{ settings()->company_name }}</h2>
                <p>
                    {{ settings()->company_phone }} <br>
                    {{ settings()->company_address }} <br>
                    {{ __('Date') }}: {{ format_date($sale->date).' — '.now()->format('H:i') }}<br>
                    {{ __('Reference') }}: #{{ $sale->id }} _ {{ $sale->reference }}<br>
                    {{ __('Name') }}: {{ $sale->customer->name }}
                </p>
            </div>
        </div>
        <div id="table" style="margin-right: -15px; margin-left: -15px;">
            <table>
                <thead>
                    <tr class="title">
                        <th colspan="2" style="text-align: left;">{{ __('Concept') }}</th>
                        <th colspan="2" style="text-align: right;">{{ __('Qty') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php($totalsaleDetailsTax = 0)
                    @foreach ($sale->saleDetailsTax as $saleDetailsTax)
                        <tr>
                            <td colspan="2" style="text-align: left;">
                                Comisión Uso Tarjeta <br>
                                <small><strong>{{ format_currency($saleDetailsTax->tax) }}</strong></small>
                            </td>
                            <td colspan="2" style="text-align: right;">
                                1
                            </td>
                        </tr>
                    @php($totalsaleDetailsTax += $saleDetailsTax->tax)
                    @endforeach

                    @foreach ($sale->saleDetails as $saleDetail)
                        <tr>
                            <td colspan="2" style="text-align: left;">
                                {{ $saleDetail->name }} <br>
                                <small><strong>{{ format_currency($saleDetail->price) }}</strong> -${{ $saleDetail->product_discount_amount * $saleDetail->quantity }} </small>
                            </td>
                            <td colspan="2" style="text-align: right;">
                                {{ $saleDetail->quantity }}
                            </td>
                        </tr>
                    @endforeach
                    @foreach ($sale->saleDetailsService as $saleDetailService)
                        <tr>
                            <td colspan="2" style="text-align: left;">
                                {{ $saleDetailService->name }} 
                                <em>
                                    {{ optional($saleDetailService->customer)->name }}
                                    @if($saleDetailService->with_days)
                                       - {{ implode(', ', $saleDetailService->with_days) }}
                                    @endif
                                    {{ $saleDetailService->hour ? '['.$saleDetailService->hour.']' : '' }}
                                </em>
                                <br>

                                <small><strong>{{ format_currency($saleDetailService->price) }}</strong> -${{ $saleDetailService->product_discount_amount * $saleDetailService->quantity }} </small>
                            </td>
                            <td colspan="2" style="text-align: right;">
                                {{ $saleDetailService->quantity }}
                            </td>
                        </tr>
                    @endforeach

                    @if (settings()->show_order_tax == true)
                        <tr>
                            <th colspan="3" style="text-align:left">{{ __('Tax') }}
                                ({{ $sale->tax_percentage }}%)</th>
                            <th style="text-align:right">{{ format_currency($sale->tax_amount) }}</th>
                        </tr>
                    @endif
                    @if (settings()->show_discount == true)
                        <tr>
                            <th colspan="3" style="text-align:left">{{ __('Discount') }}
                                ({{ $sale->discount_percentage }}%)</th>
                            <th style="text-align:right">{{ format_currency($sale->discount_amount) }}</th>
                        </tr>
                    @endif
                    @if (settings()->show_shipping == true)
                        <tr>
                            <th colspan="3" style="text-align:left">{{ __('Shipping') }}</th>
                            <th style="text-align:right">{{ format_currency($sale->shipping_amount) }}</th>
                        </tr>
                    @endif
                    <tr>
                        <th colspan="3" style="text-align:left">{{ __('Grand Total') }}</th>
                        <th style="text-align:right">{{ format_currency($sale->total_amount_with_tax) }}</th>
                    </tr>
                </tbody>
            </table>

            @foreach($sale->salepayments as $salepayments)
                <div class="centered" style="background-color:#ddd;padding: 5px; margin-bottom: 5px;">
                        {{ __('Paid By') }}: {{ __($salepayments->payment_method) }} <br>
                        {{ __('Amount') }}: {{ format_currency($salepayments->amount + $salepayments->tax) }}
                </div>
            @endforeach

            @foreach($sale->freeSwims as $free)
                {{-- <div class="centered" style="background-color:#ddd;padding: 5px; margin-bottom: 5px;">
                        {{ __('ID') }}: {{ __($salepayments->id) }} <br>
                </div> --}}

                <div id="table" style="margin-right: -5px; margin-left: -5px;">
                    <table style="border: 2px solid #000;border-style: dashed; margin-top: 5px;">
                      <tr>
                        <th style="width: 120px;">#SWIM-PASS-{{ $free->id }}</th>
                        <td  rowspan="2" style="text-align: center;">{{ $free->customer->name }}</td>
                      </tr>
                      <tr >
                        <th><img src="{{ public_path('images/logo.png') }}" alt="" width="40"/></th>
                      </tr>
                    </table>
                </div>

            @endforeach


        </div>
    </div>
</body>

</html>
