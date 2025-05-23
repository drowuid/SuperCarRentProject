<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'SuperCarRent') }}</title>

    <!-- Styles & Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        main.flex-grow {
            flex-grow: 1;
        }
    </style>
</head>
<body class="bg-gray-100">

    @include('layouts.navigation')

    <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    @include('layouts.footer')

</body>
</html>
