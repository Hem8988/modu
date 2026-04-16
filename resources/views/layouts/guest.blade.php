<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-4 sm:pt-0 px-4 bg-gray-100">
            <div class="mb-6 sm:mb-8">
                <a href="/" class="block">
                    <x-application-logo class="w-16 h-16 sm:w-20 sm:h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md bg-white shadow-md sm:rounded-lg rounded-lg overflow-hidden">
                <div class="px-4 sm:px-6 py-8">
                    {{ $slot }}
                </div>
            </div>
        </div>
        <style>
            @media (max-width: 640px) {
                body { font-size: 14px; }
                .min-h-screen { min-height: 100vh; }
            }
        </style>
    </body>
</html>
