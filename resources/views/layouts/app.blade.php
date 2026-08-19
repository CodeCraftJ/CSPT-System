<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CSP Tracker') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900">
        <div class="min-h-screen bg-slate-50 bg-hero-subtle">
            @include('layouts.navigation')

            @isset($header)
                <header class="border-b border-slate-200/80 bg-white/85 backdrop-blur-md">
                    <div class="page-container py-6 sm:py-7">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="page-container py-6 sm:py-8 lg:py-10">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
