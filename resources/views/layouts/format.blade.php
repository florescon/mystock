<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title') || {{ config('app.name') }}</title>

    <style>
    </style>

    @yield('style')

</head>

<body>

    @yield('content')

    @yield('scripts')

</body>

</html>
