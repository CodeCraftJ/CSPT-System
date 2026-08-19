<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>CSP Tracker</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 text-slate-900 font-sans antialiased">
        <div class="relative overflow-hidden bg-slate-50 bg-hero-subtle">
            <div class="pointer-events-none absolute inset-x-0 top-0 h-72 bg-gradient-to-b from-slate-900/10 to-transparent"></div>
            <div class="pointer-events-none absolute right-0 top-16 h-72 w-72 rounded-full bg-sky-400/15 blur-3xl"></div>
            <div class="pointer-events-none absolute left-0 bottom-20 h-72 w-72 rounded-full bg-emerald-400/15 blur-3xl"></div>

            <div class="page-container min-h-screen py-10">
                <header class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex h-14 w-14 items-center justify-center rounded-3xl bg-gradient-to-br from-indigo-600 to-sky-500 text-white shadow-lg">
                            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="1" x2="12" y2="23" />
                                <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                            </svg>
                        </div>
                        <div>
                            <p class="page-kicker">CSP Tracker</p>
                            <h1 class="page-title">Food supply and profit tracking</h1>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="app-btn-primary">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="app-btn-primary">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="app-btn-secondary">Staff Register</a>
                            @endif
                        @endauth
                    </div>
                </header>

                <main class="mt-10 grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
                    <section class="space-y-8">
                        <div class="app-card overflow-hidden">
                            <div class="bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 p-10 text-white">
                                <span class="page-kicker text-slate-300">Modern canteen workflow</span>
                                <h2 class="mt-5 max-w-2xl text-4xl font-semibold tracking-tight">Turn paper orders into fast, digital food tracking.</h2>
                                <p class="mt-5 max-w-2xl text-base leading-7 text-slate-300">Manage supplies, capture sales, and review profit in a simple dashboard designed for canteen staff and administrators.</p>

                                <div class="mt-8 grid gap-4 sm:grid-cols-3">
                                    <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-300">Inventory</p>
                                        <p class="mt-3 text-sm text-slate-200">Organize stock levels by item, category, and availability.</p>
                                    </div>
                                    <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-300">Orders</p>
                                        <p class="mt-3 text-sm text-slate-200">Track purchases and sales with clear summaries.</p>
                                    </div>
                                    <div class="rounded-3xl border border-white/10 bg-white/10 p-5 backdrop-blur">
                                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-300">Reports</p>
                                        <p class="mt-3 text-sm text-slate-200">Export menus and sales data to CSV for fast reporting.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-4 sm:flex-row">
                            <a href="{{ route('login') }}" class="app-btn-primary">Get Started</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="app-btn-secondary">Create Staff Account</a>
                            @endif
                        </div>
                    </section>

                    <section class="app-card overflow-hidden">
                        <div class="bg-gradient-to-br from-indigo-600 to-sky-500 p-10 text-white">
                            <p class="page-kicker text-cyan-100/90">Welcome</p>
                            <h2 class="mt-3 text-3xl font-semibold tracking-tight">Digitize food sales data and speed up transactions.</h2>
                            <p class="mt-4 text-base leading-7 text-cyan-100/90">CSP Tracker supports canteen staff and admins with simple inventory and order workflows.</p>
                            <div class="mt-8 grid gap-3 text-sm">
                                <div class="rounded-3xl bg-white/10 p-5">Modern dashboard with inventory, orders, and profit insights.</div>
                                <div class="rounded-3xl bg-white/10 p-5">Downloadable CSV reports for menu and sales documentation.</div>
                                <div class="rounded-3xl bg-white/10 p-5">Role-based access for admin and staff.</div>
                            </div>
                        </div>
                    </section>
                </main>

                <footer class="mt-10 text-center text-sm text-slate-500">© 2024 CSP Tracker. All rights reserved.</footer>
            </div>
        </div>
    </body>
</html>
