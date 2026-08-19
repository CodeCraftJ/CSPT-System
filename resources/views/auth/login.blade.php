<x-guest-layout>
    <div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr]">
        <section class="app-card-soft p-10">
            <span class="inline-flex items-center rounded-full bg-indigo-500/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.28em] text-indigo-700">CSP Tracker</span>
            <h1 class="mt-8 text-4xl font-semibold leading-tight text-slate-900">Welcome back</h1>
            <p class="mt-4 max-w-xl text-base leading-7 text-slate-600">Sign in to access your dashboard and manage inventory, orders, and profits.</p>
            <div class="mt-10 grid gap-4 sm:grid-cols-2">
                <div class="rounded-[1.75rem] bg-white p-5 ring-1 ring-slate-200/70">
                    <p class="font-semibold text-slate-900">Fast access</p>
                    <p class="mt-2 text-sm text-slate-500">Quickly review stock and order status.</p>
                </div>
                <div class="rounded-[1.75rem] bg-white p-5 ring-1 ring-slate-200/70">
                    <p class="font-semibold text-slate-900">Secure login</p>
                    <p class="mt-2 text-sm text-slate-500">Keep your data protected with verified authentication.</p>
                </div>
            </div>
        </section>

        <section class="app-card p-8">
            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">Sign in</p>
                <h2 class="mt-3 text-3xl font-semibold text-slate-900">Access your dashboard</h2>
                <p class="mt-2 text-sm text-slate-500">Enter your credentials to continue managing stock, orders, and profits.</p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ showPassword: false }">
                @csrf

                <div>
                    <label for="email" class="app-label">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="Enter your email" class="app-input" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-rose-600" />
                </div>

                <div>
                    <label for="password" class="app-label">Password</label>
                    <div class="relative mt-2">
                        <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="current-password" placeholder="Enter your password" class="app-input pr-14" />
                        <button
                            type="button"
                            @click="showPassword = ! showPassword"
                            :aria-label="showPassword ? 'Hide password' : 'Show password'"
                            :title="showPassword ? 'Hide password' : 'Show password'"
                            style="position:absolute; right:0.75rem; top:50%; transform:translateY(-50%); width:2.25rem; height:2.25rem; display:flex; align-items:center; justify-content:center; border-radius:9999px; color:#64748b; background:transparent;"
                            onmouseenter="this.style.backgroundColor='#f1f5f9'; this.style.color='#334155';"
                            onmouseleave="this.style.backgroundColor='transparent'; this.style.color='#64748b';"
                        >
                            <svg x-show="!showPassword" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 3c4.5 0 8.4 2.7 9.8 7-.1.3-.2.6-.4.9-1.8 3.6-5.2 5.9-9.4 5.9S2.7 14.5.9 10.9C.7 10.6.6 10.3.5 10c1.4-4.3 5.3-7 9.5-7Zm0 2c-3.1 0-6 1.9-7.2 5 .7 1.7 2 3.1 3.5 4 1.1-1.3 2.3-2 3.7-2s2.6.7 3.7 2c1.6-.9 2.8-2.3 3.5-4C16 6.9 13.1 5 10 5Zm0 2.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5Z" />
                            </svg>
                            <svg x-show="showPassword" x-cloak class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3.3 2.3 17.7 16.7l-1.4 1.4-2-2c-1.2.4-2.6.6-4.3.6-4.2 0-7.6-2.3-9.4-5.8-.2-.3-.3-.6-.4-.9.6-1.9 1.8-3.6 3.3-4.9L1.9 3.7l1.4-1.4ZM10 5c3.1 0 6 1.9 7.2 5-.5 1.3-1.2 2.4-2.1 3.4l-1.5-1.5c.5-.8.8-1.7.8-2.7-1.2-3.1-4.1-5-7.2-5-.9 0-1.8.2-2.6.5L3.6 3.8C5.5 2.9 7.6 2.5 10 2.5c4.2 0 8.1 2.7 9.5 7-.1.3-.2.6-.4.9-.5 1-1.1 2-1.9 2.8l-1.4-1.4c.5-.6.9-1.3 1.2-2.1C16 6.9 13.1 5 10 5Zm0 2.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5Z" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-rose-600" />
                </div>

                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center gap-3 text-sm text-slate-600">
                        <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" />
                        <span>Remember me</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">Forgot your password?</a>
                    @endif
                </div>

                <button type="submit" class="app-btn-primary w-full">Sign in</button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-500">Need a staff account? <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-700">Create one</a></p>
        </section>
    </div>
</x-guest-layout>
