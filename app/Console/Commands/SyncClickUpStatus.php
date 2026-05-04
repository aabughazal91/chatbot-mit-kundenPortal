<?php

namespace App\Console\Commands;

use App\Models\ClickUpMapping;
use App\Services\ClickUpService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncClickUpStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clickup:sync-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Holt den aktuellen Status der Tasks von ClickUp API und aktualisiert die lokale Datenbank';

    /**
     * Execute the console command.
     */
    public function handle(ClickUpService $clickUpService)
    {
        Log::info('Starting ClickUp status sync...');

        // 1. Abrufen aller Datensätze, die aktualisiert werden müssen
        $mappings = ClickUpMapping::all();

        if ($mappings->isEmpty()) {
            $this->warn('Keine ClickUp-Mappings gefunden.');

            return;
        }

        foreach ($mappings as $mapping) {

            try {
                Log::info("Syncing Task ID: {$mapping->clickup_aufgabe_id}");

                // 2. API-Aufruf
                $response = $clickUpService->getTaskStatus($mapping->clickup_aufgabe_id);

                if (! $response || empty($response['status_name'])) {
                    Log::error("API returned invalid response for Task ID: {$mapping->clickup_aufgabe_id}");
                    $mapping->zuletzt_synchronisiert_am = Carbon::now();
                    $mapping->save();

                    continue;
                }

                $newStatus = $response['status_name'];

                // 3. Datenbankaktualisierung                
                $mapping->clickup_status_name = $newStatus;
                $mapping->zuletzt_synchronisiert_am = Carbon::now();
                $mapping->save();

                Log::info("Task {$mapping->clickup_aufgabe_id} updated successfully. Status: {$newStatus}");
            } catch (\Exception $e) {

                // 4. Fehlerbehandlung ohne Anhalten der Schleife
                Log::error("Failed syncing Task ID {$mapping->clickup_aufgabe_id}: ".$e->getMessage());

                // Aktualisiere die Zeit des letzten Synchronisationsversuchs, auch wenn er fehlgeschlagen ist
                $mapping->zuletzt_synchronisiert_am = Carbon::now();
                $mapping->save();

                continue;
            }
        }

        Log::info('ClickUp status sync completed.');
    }
}
