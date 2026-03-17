<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PriceModule;
class PriceModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PriceModule::create([
            'key' => 'design_type',
            'label_de' => 'Was benötigen Sie?',
            'description' => 'Neues Design oder Überarbeitung',
            'price' => 0,
            'type' => 'select',
            'category' => 'base',
            'is_active' => true
        ]);

        PriceModule::create([
            'key' => 'anzahl_der_seiten',
            'label_de' => 'Anzahl der Seiten',
            'description' => 'Wie viele Seiten benötigen Sie?',
            'price' => 0,
            'type' => 'select',
            'category' => 'base',
            'is_active' => true
        ]);

        PriceModule::create([
            'key' => 'cms_basic',
            'label_de' => 'Webseite mit CMS',
            'description' => 'Verwaltung von Inhalte über ein Backend',
            'price' => 1500.00,
            'type' => 'boolean',
            'category' => 'base',
            'is_active' => true
        ]);
    }

}
