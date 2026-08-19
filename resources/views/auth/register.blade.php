<x-guest-layout>
    <div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr]">
        <section class="app-card-soft p-10 bg-gradient-to-br from-indigo-600 to-sky-500 text-white">
            <span class="inline-flex items-center rounded-full bg-white/10 px-4 py-1 text-xs font-semibold uppercase tracking-[0.28em] text-white/90">Create account</span>
            <h1 class="mt-8 text-4xl font-semibold leading-tight text-white">Get your canteen data running on a system built for teams.</h1>
            <p class="mt-4 max-w-xl text-base leading-7 text-slate-100">Set up your CSP Tracker account to manage supplies, menu sales, and profit analysis in one place.</p>
            <div class="mt-10 grid gap-4 sm:grid-cols-2">
                <div class="rounded-[1.75rem] bg-white/10 p-5 ring-1 ring-white/15">
                    <p class="font-semibold text-white">Quick setup</p>
                    <p class="mt-2 text-sm text-slate-200">Register fast and start capturing sales immediately.</p>
                </div>
                <div class="rounded-[1.75rem] bg-white/10 p-5 ring-1 ring-white/15">
                    <p class="font-semibold text-white">Secure access</p>
                    <p class="mt-2 text-sm text-slate-200">Control users with role-based login access.</p>
                </div>
                <div class="rounded-[1.75rem] bg-white/10 p-5 ring-1 ring-white/15">
                    <p class="font-semibold text-white">Inventory first</p>
                    <p class="mt-2 text-sm text-slate-200">Keep food stocks, pricing, and costs in sync.</p>
                </div>
                <div class="rounded-[1.75rem] bg-white/10 p-5 ring-1 ring-white/15">
                    <p class="font-semibold text-white">Sales insights</p>
                    <p class="mt-2 text-sm text-slate-200">Track order totals and profit performance.</p>
                </div>
            </div>
        </section>

        <section class="app-card p-8">
            <div class="mb-8">
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">Create account</p>
                <h2 class="mt-3 text-3xl font-semibold text-slate-900">Start your CSP Tracker account</h2>
                <p class="mt-2 text-sm text-slate-500">Fill in your information below to register a staff account.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-5" x-data="{ showPassword: false, showConfirmation: false }">
                @csrf

                <div>
                    <label for="name" class="app-label">Full Name</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" placeholder="Enter your full name" class="app-input" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-rose-600" />
                </div>

                <div>
                    <label for="email" class="app-label">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" placeholder="Enter your email" class="app-input" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-rose-600" />
                </div>

                <div>
                    <label for="password" class="app-label">Password</label>
                    <div class="relative mt-2">
                        <input id="password" :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="new-password" placeholder="Create a strong password" class="app-input pr-14" />
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

                <div>
                    <label for="password_confirmation" class="app-label">Confirm Password</label>
                    <div class="relative mt-2">
                        <input id="password_confirmation" :type="showConfirmation ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm your password" class="app-input pr-14" />
                        <button
                            type="button"
                            @click="showConfirmation = ! showConfirmation"
                            :aria-label="showConfirmation ? 'Hide confirm password' : 'Show confirm password'"
                            :title="showConfirmation ? 'Hide confirm password' : 'Show confirm password'"
                            style="position:absolute; right:0.75rem; top:50%; transform:translateY(-50%); width:2.25rem; height:2.25rem; display:flex; align-items:center; justify-content:center; border-radius:9999px; color:#64748b; background:transparent;"
                            onmouseenter="this.style.backgroundColor='#f1f5f9'; this.style.color='#334155';"
                            onmouseleave="this.style.backgroundColor='transparent'; this.style.color='#64748b';"
                        >
                            <svg x-show="!showConfirmation" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 3c4.5 0 8.4 2.7 9.8 7-.1.3-.2.6-.4.9-1.8 3.6-5.2 5.9-9.4 5.9S2.7 14.5.9 10.9C.7 10.6.6 10.3.5 10c1.4-4.3 5.3-7 9.5-7Zm0 2c-3.1 0-6 1.9-7.2 5 .7 1.7 2 3.1 3.5 4 1.1-1.3 2.3-2 3.7-2s2.6.7 3.7 2c1.6-.9 2.8-2.3 3.5-4C16 6.9 13.1 5 10 5Zm0 2.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5Z" />
                            </svg>
                            <svg x-show="showConfirmation" x-cloak class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M3.3 2.3 17.7 16.7l-1.4 1.4-2-2c-1.2.4-2.6.6-4.3.6-4.2 0-7.6-2.3-9.4-5.8-.2-.3-.3-.6-.4-.9.6-1.9 1.8-3.6 3.3-4.9L1.9 3.7l1.4-1.4ZM10 5c3.1 0 6 1.9 7.2 5-.5 1.3-1.2 2.4-2.1 3.4l-1.5-1.5c.5-.8.8-1.7.8-2.7-1.2-3.1-4.1-5-7.2-5-.9 0-1.8.2-2.6.5L3.6 3.8C5.5 2.9 7.6 2.5 10 2.5c4.2 0 8.1 2.7 9.5 7-.1.3-.2.6-.4.9-.5 1-1.1 2-1.9 2.8l-1.4-1.4c.5-.6.9-1.3 1.2-2.1C16 6.9 13.1 5 10 5Zm0 2.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5Z" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-rose-600" />
                </div>

                <button type="submit" class="app-btn-primary w-full">Create account</button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-500">Already have a staff account? <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-700">Sign in</a></p>
        </section>
    </div>
</x-guest-layout>
