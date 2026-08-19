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
    <body class="min-h-screen bg-slate-50 text-slate-900 font-sans antialiased">
        <div class="relative min-h-screen overflow-hidden bg-slate-50 bg-hero-subtle">
            <div class="pointer-events-none absolute inset-x-0 top-0 h-72 bg-gradient-to-b from-indigo-600/12 to-transparent"></div>
            <div class="pointer-events-none absolute right-[-7rem] top-20 h-72 w-72 rounded-full bg-sky-400/15 blur-3xl"></div>
            <div class="pointer-events-none absolute left-[-7rem] bottom-20 h-72 w-72 rounded-full bg-emerald-400/15 blur-3xl"></div>

            <div class="flex min-h-screen items-center justify-center px-4 py-12 sm:px-6 lg:px-8">
                <div class="w-full max-w-5xl">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
