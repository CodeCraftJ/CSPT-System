<x-guest-layout>
    <div class="mx-auto w-full max-w-md">
        <section class="app-card p-8">
            <div class="mb-8 text-center">
                <div class="mx-auto mb-6 flex h-16 w-16 items-center justify-center rounded-3xl bg-gradient-to-br from-indigo-600 to-sky-500 text-white shadow-lg">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16v16H4z"></path>
                        <path d="M22 6L12 13 2 6"></path>
                    </svg>
                </div>
                <h1 class="text-2xl font-semibold text-slate-900">Verify your email</h1>
                <p class="mt-2 text-sm text-slate-500">Before continuing, please check your email for a verification link.</p>
            </div>

            <div class="space-y-4">
                <p class="text-sm text-slate-600">{{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}</p>

                @if (session('status') == 'verification-link-sent')
                    <div class="rounded-2xl bg-emerald-50 p-4 text-sm text-emerald-800 ring-1 ring-emerald-200">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </div>
                @endif

                <div class="flex flex-col gap-3 sm:flex-row sm:justify-between">
                    <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="app-btn-primary w-full">Resend Verification Email</button>
                    </form>
                    <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="app-btn-secondary w-full">Log Out</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</x-guest-layout>
