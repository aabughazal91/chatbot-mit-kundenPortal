<x-guest-layout>
    <div class="mb-4 text-muted small">
        {{ __('Vielen Dank für Ihre Anmeldung! Bevor Sie beginnen, bestätigen Sie bitte Ihre E-Mail-Adresse, indem Sie auf den Link in der soeben zugesandten E-Mail klicken. Sollten Sie die E-Mail nicht erhalten haben, senden wir Ihnen gerne eine weitere zu.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 small fw-medium text-success">
            {{ __('Ein neuer Verifizierungslink wurde an die von Ihnen bei der Registrierung angegebene E-Mail-Adresse gesendet.') }}
        </div>
    @endif

    <div class="mt-4 d-flex align-items-center justify-content-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Verifizierungs-E-Mail erneut senden') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="btn btn-link text-decoration-none small text-muted p-0 m-0 align-baseline">
                {{ __('Abmelden') }}
            </button>
        </form>
    </div>
</x-guest-layout>
