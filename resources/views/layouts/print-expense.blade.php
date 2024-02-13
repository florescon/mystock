<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') || {{ config('app.name') }}</title>
    
    <style>
        .invoice-box {
            width: 800px;
            height: 480px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
/*            box-shadow: 0 0 10px rgba(0, 0, 0, .15);*/
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }
        
        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }
        
        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }
        
        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }
        
        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }
        
        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }
        
        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }
        
        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }
        
        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }
        
        .invoice-box table tr.item td{
            border-bottom: 1px solid #eee;
        }
        
        .invoice-box table tr.item.last td {
            border-bottom: none;
        }
        
        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }
        
        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }
            
            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }
        
        /** RTL **/
        .rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }
        
        .rtl table {
            text-align: right;
        }
        
        .rtl table tr td:nth-child(2) {
            text-align: left;
        }
    </style>
</head>

<body>
    @foreach($expenses as $expense)
    <div class="invoice-box" style="margin-top: 10px;">
        <table cellpadding="0" cellspacing="0">
           <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="{{ public_path('images/logo.png') }}" alt="" width="80"/>
                            </td>
                            
                            <td>
                                <h2 style="margin-bottom: 5px;font-size: 16px;">{{ settings()->company_name }}</h2>
                                {{ settings()->company_phone }} <br>
                                {{ settings()->company_address }} <br>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
 
            <tr class="information">
                <td colspan="2">
                    <strong>Folio:</strong> #{{ $expense->id }}<br>
                    <strong>Creado [Y-m-d  H:i:s]:</strong> {{ $expense->created_at }} <em></em><br><br>

                    <table>
                         <thead>
                            <tr class="title" style="background-color:#ddd">
                                <th style="text-align: left;">{{ __('Reference') }}</th>
                                <th style="text-align: center;">{{ __('Details') }}</th>
                                <th style="text-align: center;">{{ __('User') }}</th>
                                <th style="text-align: center;">{{ __('Amount') }}</th>
                            </tr>
                        </thead>
                        <tr>
                            <td style="text-align: left;">
                                {{ $expense->reference }}
                            </td>
                            <td style="text-align: left;">
                                {{ $expense->details ?? '--' }}
                            </td>
                            <td style="text-align: center;">
                                {{ $expense->customer_id ? optional($expense->customer)->name : '--' }}
                            </td>
                            <td style="text-align: center;">
                                {{ format_currency($expense->amount) }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
        </table>
 
    </div>
    @endforeach
</body>
</html>