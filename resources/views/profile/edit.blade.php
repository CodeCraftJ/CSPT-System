<x-app-layout>
    <x-slot name="header">
        <div class="page-header">
            <div>
                <div class="page-kicker">Account</div>
                <h2 class="page-title">Profile</h2>
                <p class="page-subtitle">Manage your account details, password, and preferences in one place.</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="app-surface p-6">
            <div class="max-w-2xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </section>

        <section class="app-surface p-6">
            <div class="max-w-2xl">
                @include('profile.partials.update-password-form')
            </div>
        </section>

        <section class="app-surface p-6">
            <div class="max-w-2xl">
                @include('profile.partials.delete-user-form')
            </div>
        </section>
    </div>
</x-app-layout>
