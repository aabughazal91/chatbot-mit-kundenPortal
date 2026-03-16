<?php

use App\Models\User;

foreach (User::all() as $user) {
    if (!$user->is_confirmed) {
        $user->is_confirmed = 1;
        $user->save();
        echo "Confirmed user: {$user->email}\n";
    }
}
echo "Done.\n";
