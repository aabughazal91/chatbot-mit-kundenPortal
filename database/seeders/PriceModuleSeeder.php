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
    'key' => 'cms_basic',
    'label_de' => 'Webseite mit CMS',
    'description' => 'Verwaltung von Inhalte über ein Backend',
    'price' => 1500.00,
    'type' => 'boolean',
    'category' => 'base'
    ]);
    }

}
