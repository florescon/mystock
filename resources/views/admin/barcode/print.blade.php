<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ __('Barcodes') }}</title>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            @foreach ($barcodes as $barcode)
                <div class="col-xs-3" style="border: 1px solid #dddddd;border-style: dashed; text-align: center;">
                    <p style="font-size: 7px;color: #000;margin-top: 1px;margin-bottom: 1px; text-align: center;">
                        {{ $barcode['name'] }}

                    </p>
                    {{-- read $barcode as svg image --}}
                    <img src="data:image/svg+xml;base64,{{ base64_encode($barcode['barcode']) }}" alt="barcode" />

                    <p style="font-size: 6px;color: #000;font-weight: bold; text-align: center;">
                        {{ __('Price') }}: {{ $barcode['price'] }} MX
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</body>

</html>
