<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Auth\Notifications\ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('Passwort zurücksetzen - agentur-77')
                ->greeting('Hallo ' . ($notifiable->name ?? '') . '!')
                ->line('Du erhältst diese E-Mail, weil wir eine Anfrage zum Zurücksetzen des Passworts für dein Konto erhalten haben.')
                ->action('Passwort zurücksetzen', url(route('password.reset', [
                    'token' => $token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ], false)))
                ->line('Dieser Link verfällt in ' . config('auth.passwords.'.config('auth.defaults.passwords').'.expire') . ' Minuten.')
                ->line('Wenn du diese Anfrage nicht gestellt hast, kannst du diese E-Mail einfach ignorieren.');
        });
    }
}
