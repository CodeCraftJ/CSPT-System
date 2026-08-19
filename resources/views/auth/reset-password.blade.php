<x-guest-layout>
    <div class="mx-auto w-full max-w-md">
        <section class="app-card p-8">
            <div class="mb-8 text-center">
                <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-indigo-600 to-sky-500 text-white shadow-lg">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 1v4"></path>
                        <path d="M6.5 7.5a5.5 5.5 0 0 1 11 0v3"></path>
                        <path d="M5 12h14"></path>
                        <path d="M5 21h14"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-semibold text-slate-900">Reset your password</h1>
                <p class="mt-2 text-sm text-slate-500">Enter your email and choose a new secure password.</p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                @csrf

                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label for="email" class="app-label">Email</label>
                    <input id="email" class="app-input" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-rose-600" />
                </div>

                <div>
                    <label for="password" class="app-label">Password</label>
                    <input id="password" class="app-input" type="password" name="password" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-rose-600" />
                </div>

                <div>
                    <label for="password_confirmation" class="app-label">Confirm Password</label>
                    <input id="password_confirmation" class="app-input" type="password" name="password_confirmation" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-rose-600" />
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="app-btn-primary">Reset Password</button>
                </div>
            </form>
        </section>
    </div>
</x-guest-layout>
