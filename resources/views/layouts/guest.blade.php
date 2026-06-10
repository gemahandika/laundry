<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'So Fresh Laundry') }}</title>
    <!-- LOGO KUSTOM SO FRESH LAUNDRY UNTUK TAB BROWSER -->
    <link rel="icon" type="image/svg+xml"
        href="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><defs><linearGradient id='aquaGrad' x1='0%' y1='0%' x2='100%' y2='100%'><stop offset='0%' stop-color='%230ea5e9'/><stop offset='100%' stop-color='%2310b981'/></linearGradient></defs><rect width='100' height='100' rx='25' fill='url(%23aquaGrad)'/><circle cx='40' cy='45' r='16' fill='white' opacity='0.2'/><circle cx='52' cy='52' r='22' fill='none' stroke='white' stroke-width='6' stroke-linecap='round'/><circle cx='70' cy='35' r='8' fill='none' stroke='white' stroke-width='3'/><path d='M 36 50 Q 52 65 68 50' fill='none' stroke='white' stroke-width='5' stroke-linecap='round'/><circle cx='45' cy='42' r='3' fill='white'/><circle cx='59' cy='42' r='3' fill='white'/></svg>">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased m-0 p-0 box-border bg-gray-50">
    {{ $slot }}
</body>

</html>
