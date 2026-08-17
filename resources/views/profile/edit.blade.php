<x-app-layout>
    <div class="space-y-6">
        <x-page-header title="Pengaturan Profil" description="Kelola informasi akun, email, dan kata sandi Anda." />

        <div class="crm-card p-6">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="crm-card p-6">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="crm-card p-6 border-l-4 border-primary">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>

