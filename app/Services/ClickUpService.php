<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ClickUpService
{
    protected string $apiKey;

    protected string $baseUrl = 'https://api.clickup.com/api/v2';

    public function __construct()
    {
        // Wir rufen das Token aus der Konfiguration ab, die wiederum auf .env basiert
        $this->apiKey = config('services.clickup.token') ?? '';
        if (empty($this->apiKey)) {
            Log::warning('ClickUpService: API key is missing.');
        } else {
            Log::info('ClickUpService: API key is configured (length: '.strlen($this->apiKey).')');
        }
    }

    /**
     * Fetch the current status of a task from ClickUp.
     *
     * @return array|null Returns task status array or null if failed.
     */
    public function getTaskStatus(string $taskId): ?array
    {
        if (empty($this->apiKey)) {
            Log::warning("ClickUpService: API key is missing. Cannot fetch task status for {$taskId}.");

            return null;
        }

        try {
            // Anfrage an ClickUp API senden
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
                'Content-Type' => 'application/json',
            ])->get("{$this->baseUrl}/task/{$taskId}");

            // Überprüfung des Erfolgs der Antwort
            if ($response->successful()) {
                $data = $response->json();

                return [
                    'status_name' => $data['status']['status'] ?? null,
                    'raw_response' => $data,
                ];
            }

            // Fehlerbehandlung und Protokollierung (Rate limits, Not Found, etc.)
            $status = $response->status();
            $body = $response->body();

            if ($status === 429) {
                Log::warning("ClickUpService: Rate limit exceeded when fetching task {$taskId}.");
            } elseif ($status === 404) {
                Log::error("ClickUpService: Task {$taskId} not found in ClickUp.");
            } else {
                Log::error("ClickUpService: Failed to fetch task {$taskId}. Status: {$status}, Body: {$body}");
            }

            return null;
        } catch (Exception $e) {
            // Umgang mit Internetunterbrechungen oder Serverfehlern (wichtig für IHK)
            Log::error("ClickUpService: Exception while fetching task {$taskId}. Message: ".$e->getMessage());

            return null;
        }
    }
}
