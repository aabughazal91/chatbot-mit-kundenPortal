<section class="mt-4">
    <header>
        <h2 class="h5 fw-semibold text-dark">
            {{ __('Konto löschen') }}
        </h2>

        <p class="mt-1 text-muted small">
            {{ __('Sobald Ihr Konto gelöscht ist, werden alle seine Ressourcen und Daten dauerhaft gelöscht. Bitte laden Sie vor dem Löschen Ihres Kontos alle Daten oder Informationen herunter, die Sie behalten möchten.') }}
        </p>
    </header>

    <x-danger-button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('Delete Account') }}</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-4">
            @csrf
            @method('delete')

            <h2 class="h5 fw-semibold text-dark mb-3">
                {{ __('Sind Sie sicher, dass Sie Ihr Konto löschen möchten?') }}
            </h2>

            <p class="text-muted small mb-4">
                {{ __('Sobald Ihr Konto gelöscht ist, werden alle seine Ressourcen und Daten dauerhaft gelöscht. Bitte laden Sie vor dem Löschen Ihres Kontos alle Daten oder Informationen herunter, die Sie behalten möchten.') }}
            </p>

            <div class="mb-4">
                <x-input-label for="password" value="{{ __('Password') }}" class="visually-hidden" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 w-75"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="d-flex justify-content-end gap-3 mt-4">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button>
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
