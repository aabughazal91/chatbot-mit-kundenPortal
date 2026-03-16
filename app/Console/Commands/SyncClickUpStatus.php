<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ClickUpMapping;
use App\Services\ClickUpService;
use Carbon\Carbon;
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

        // 1. جلب جميع السجلات التي تحتاج تحديث
        $mappings = ClickUpMapping::all();

        if ($mappings->isEmpty()) {
            $this->warn('Keine ClickUp-Mappings gefunden.');
            return;
        }

        foreach ($mappings as $mapping) {

            try {
                Log::info("Syncing Task ID: {$mapping->clickup_task_id}");

                // 2. الاتصال بالـ API
                $response = $clickUpService->getTaskStatus($mapping->clickup_task_id);

                if (!$response || empty($response['status_name'])) {
                    Log::error("API returned invalid response for Task ID: {$mapping->clickup_task_id}");
                    $mapping->last_synced_at = Carbon::now();
                    $mapping->save();
                    continue;
                }

                $newStatus = $response['status_name'];

                // 3. تحديث قاعدة البيانات
                $mapping->clickup_status_name = $newStatus;
                $mapping->last_synced_at = Carbon::now();
                $mapping->save();

                Log::info("Task {$mapping->clickup_task_id} updated successfully. Status: {$newStatus}");
            } catch (\Exception $e) {

                // 4. معالجة حالات الفشل دون إيقاف الـ Loop
                Log::error("Failed syncing Task ID {$mapping->clickup_task_id}: " . $e->getMessage());

                // تحديث وقت آخر محاولة مزامنة حتى لو فشل
                $mapping->last_synced_at = Carbon::now();
                $mapping->save();

                continue;
            }
        }

        Log::info('ClickUp status sync completed.');
    }
}
