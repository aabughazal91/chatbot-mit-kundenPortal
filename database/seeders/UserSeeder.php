<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'kunde@test.com'],
            [
                'name' => 'Max Mustermann',
                'password' => Hash::make('test1234'),
                'is_admin' => false,
                'role' => 'customer',
                'is_confirmed' => true,
                'company' => 'Muster GmbH',
                'phone' => '+49 123 456789',
                'street' => 'Musterstraße 1',
                'zip' => '12345',
                'city' => 'Musterstadt',
            ]
        );
    }
}
