<x-guest-layout>
    <div class="mx-auto w-full max-w-md">
        <section class="app-card p-8">
            <div class="mb-8 text-center">
                <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-indigo-600 to-sky-500 text-white shadow-lg">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 1v4"></path>
                        <path d="M5 10h14"></path>
                        <path d="M8 10v7a4 4 0 0 0 8 0v-7"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-semibold text-slate-900">Confirm your password</h1>
                <p class="mt-2 text-sm text-slate-500">This is a secure area of the application. Please confirm your password before continuing.</p>
            </div>

            <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                @csrf

                <div>
                    <label for="password" class="app-label">Password</label>
                    <input id="password" class="app-input" type="password" name="password" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-rose-600" />
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="app-btn-primary">Confirm</button>
                </div>
            </form>
        </section>
    </div>
</x-guest-layout>
