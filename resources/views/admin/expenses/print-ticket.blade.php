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
                    {{ __('Date') }}: {{ format_date($sale->date).' — '.now()->format('H:i') }}<br><br>
                    {{ __('Folio') .' _ ' .__('Reference') }}: #{{ $sale->id }} _ {{ $sale->reference }}<br>
                    {{ optional($sale->customer)->name }}
                </p>
            </div>
        </div>
        <div id="table" style="margin-right: -15px; margin-left: -15px;">
            <table>
                <thead>
                    <tr class="title">
                        <th style="text-align: left;">{{ __('Reference') }}</th>
                        <th style="text-align: right;">{{ __('Amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td  style="text-align: left;">
                            {{ $sale->reference }} <br>
                        </td>
                        <td  style="text-align: right;">
                            <small><strong>{{ format_currency($sale->amount) }}</strong>  </small>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: left;">
                            {{ $sale->details ?? '--' }} <br>
                        </td>
                    </tr>

                </tbody>
            </table>

        </div>
    </div>
</body>

</html>
