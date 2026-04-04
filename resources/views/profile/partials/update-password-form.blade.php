<section>
    <header>
        <h2 class="h5 fw-semibold text-dark">
            {{ __('Passwort ändern') }}
        </h2>

        <p class="mt-1 text-muted small">
            {{ __('Stellen Sie sicher, dass Ihr Konto ein langes, zufälliges Passwort verwendet, um sicher zu bleiben.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-4">
        @csrf
        @method('put')

        <div class="mb-3">
            <x-input-label-profile for="update_password_current_password" :value="__('Aktuelles Passwort')" />
            <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 w-100" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div class="mb-3">
            <x-input-label-profile for="update_password_password" :value="__('Neues Passwort')" />
            <x-text-input id="update_password_password" name="password" type="password" class="mt-1 w-100" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div class="mb-3">
            <x-input-label-profile for="update_password_password_confirmation" :value="__('Passwort bestätigen')" />
            <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 w-100" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="d-flex align-items-center gap-3">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'password-updated')
                <p class="small text-muted mb-0"
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
