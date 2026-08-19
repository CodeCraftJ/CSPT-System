<nav x-data="{ open: false }" class="relative z-[100] border-b border-slate-200/80 bg-white/90 shadow-sm backdrop-blur-md">
    <div class="page-container">
        <div class="flex min-h-20 items-center justify-between gap-4 py-3">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-3 rounded-3xl bg-slate-950 px-4 py-2 text-white shadow-lg shadow-slate-900/10 transition duration-200 hover:-translate-y-0.5 hover:bg-slate-900">
                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-3xl bg-gradient-to-br from-indigo-600 to-sky-500 text-white shadow-lg">
                        <x-application-logo class="block h-5 w-5 fill-current text-white" />
                    </span>
                    <span class="font-semibold tracking-tight">CSP Tracker</span>
                </a>

                <div class="hidden items-center gap-2 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'staff')
                        <x-nav-link :href="route('orders.create')" :active="request()->routeIs('orders.create')">
                            {{ __('Create Order') }}
                        </x-nav-link>
                        <x-nav-link :href="route('food_items.index')" :active="request()->routeIs('food_items.*')">
                            {{ __('Food Items') }}
                        </x-nav-link>
                        <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                            {{ __('Orders') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden items-center gap-4 sm:flex">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50 focus:outline-none">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="h-4 w-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="sm:hidden">
                <button @click="open = ! open" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-200 bg-white text-slate-600 shadow-sm hover:bg-slate-50 focus:outline-none">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="relative z-[110] hidden sm:hidden">
        <div class="space-y-1 border-t border-slate-200 bg-white px-4 pb-4 pt-2 shadow-lg">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'staff')
                <x-responsive-nav-link :href="route('orders.create')" :active="request()->routeIs('orders.create')">
                    {{ __('Create Order') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('food_items.index')" :active="request()->routeIs('food_items.*')">
                    {{ __('Food Items') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                    {{ __('Orders') }}
                </x-responsive-nav-link>
            @endif

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                    {{ __('Log Out') }}
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</nav>
