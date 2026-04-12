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
                'role' => 'kunde',
                'is_confirmed' => true,
                'firma' => 'Muster GmbH',
                'tel' => '+49 123 456789',
                'strasse' => 'Musterstraße 1',
                'zip_stadt' => '12345 Musterstadt',
            ]
        );
        User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('test1234'),
                'is_admin' => true,
                'role' => 'admin',
                'is_confirmed' => true,
            ]
        );
    }
}
