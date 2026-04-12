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
        PriceModule::firstOrCreate(
            ['key' => 'design_type'],
            [
                'bezeichnung_de' => 'Was benötigen Sie?',
                'beschreibung' => 'Neues Design oder Überarbeitung',
                'preis' => 0,
                'typ' => 'select',
                'kategorie' => 'base',
                'ist_aktiv' => true
            ]
        );

        PriceModule::firstOrCreate(
            ['key' => 'anzahl_der_seiten'],
            [
                'bezeichnung_de' => 'Anzahl der Seiten',
                'beschreibung' => 'Wie viele Seiten benötigen Sie?',
                'preis' => 0,
                'typ' => 'select',
                'kategorie' => 'base',
                'ist_aktiv' => true
            ]
        );

        PriceModule::firstOrCreate(
            ['key' => 'cms_basic'],
            [
                'bezeichnung_de' => 'Webseite mit CMS',
                'beschreibung' => 'Verwaltung von Inhalte über ein Backend',
                'preis' => 1500.00,
                'typ' => 'boolean',
                'kategorie' => 'base',
                'ist_aktiv' => true
            ]
        );
    }

}
