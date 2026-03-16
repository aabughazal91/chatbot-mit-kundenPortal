<?php

use App\Models\User;
use App\Mail\WelcomeCustomerMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

$user = User::first();
$password = 'TestPassword123!';

if (!$user) {
    echo "Kein Benutzer gefunden!\n";
    exit;
}

try {
    Mail::to($user->email)->send(new WelcomeCustomerMail($user, $password));
    echo "E-Mail wurde an " . $user->email . " gesendet!\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
