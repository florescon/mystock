<!DOCTYPE html>
<html x-data="mainState" :class="{ dark: isDarkMode, rtl: isRtl }" class="scroll-smooth"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') || {{ config('app.name') }}</title>
    <!-- Styles -->
    <style>
        [x-cloak] {
            display: none;
        }
    </style>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/logo.png') }}">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @include('includes.main-css')
</head>

<body class="antialiased bg-gray-50 text-body font-body" dir="ltr">
        <div class="min-h-screen">

            <x-navbar-pos />

            <main class="pt-2 flex-1">
                @yield('content')
                @isset($slot)
                    {{ $slot }}
                @endisset
            </main>

            <livewire:services.associate>

            @can('sale_access')
            <livewire:sales.recent />
            @endcan

            @can('product_create')
            <livewire:products.create />
            @endcan

            @can('customer_create')
            <livewire:customers.create />
            @endcan

            {{-- <x-settings-bar /> --}}

        </div>

    @include('includes.main-js')

</body>

</html>
