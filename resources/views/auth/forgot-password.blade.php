<x-guest-layout>
    <div class="mx-auto w-full max-w-2xl">
        <section class="app-card p-10">
            <div class="mb-8 grid gap-6 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-slate-500">Reset password</p>
                    <h1 class="mt-3 text-3xl font-semibold text-slate-900">Recover access to your account.</h1>
                    <p class="mt-4 text-sm leading-6 text-slate-500">Enter the email address tied to your CSP Tracker account. We will send you a secure reset link.</p>
                </div>
                <div class="app-card-soft">
                    <p class="text-sm font-semibold text-slate-900">Need help?</p>
                    <p class="mt-2 text-sm text-slate-600">If you don’t receive the email, check your spam folder or contact your administrator.</p>
                </div>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="app-label">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Enter your email address" class="app-input" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-rose-600" />
                </div>
                <button type="submit" class="app-btn-primary w-full">Send reset link</button>
            </form>

            <p class="mt-6 text-center text-sm text-slate-500">Remember your password? <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-700">Sign in</a></p>
        </section>
    </div>
</x-guest-layout>
