<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold text-dark mb-0">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-4 bg-light text-dark">
        <div class="container d-flex flex-column gap-4 text-dark">
            <div class="card shadow-sm border-0 bg-light text-dark" >
                <div class="card-body p-4" style="max-width: 600px; color: black;">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card shadow-sm border-0 bg-light text-dark">
                <div class="card-body p-4" style="max-width: 600px;">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="card shadow-sm border-0 bg-light text-dark">
                <div class="card-body p-4" style="max-width: 600px;">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
