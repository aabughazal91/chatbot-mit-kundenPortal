<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class ClickUpService
{
    protected string $apiKey;
    protected string $baseUrl = 'https://api.clickup.com/api/v2';

    public function __construct()
    {
        // نستدعي التوكن من الكونفج الذي يعتمد بدوره على .env
        $this->apiKey = config('services.clickup.token') ?? '';
    }

    /**
     * Fetch the current status of a task from ClickUp.
     * 
     * @param string $taskId
     * @return array|null Returns task status array or null if failed.
     */
    public function getTaskStatus(string $taskId): ?array
    {
        if (empty($this->apiKey)) {
            Log::warning("ClickUpService: API key is missing. Cannot fetch task status for {$taskId}.");
            return null;
        }

        try {
            // إرسال الطلب لـ ClickUp API 
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
                'Content-Type'  => 'application/json',
            ])->get("{$this->baseUrl}/task/{$taskId}");

            // التحقق من نجاح الرد
            if ($response->successful()) {
                $data = $response->json();

                return [
                    'status_name' => $data['status']['status'] ?? null,
                    'raw_response' => $data,
                ];
            }

            // التعامل مع الأخطاء وإرسالها للسجل (Rate limits, Not Found, etc.)
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
            // التعامل مع انقطاع الإنترنت أو أخطاء السيرفر (مهم للـ IHK)
            Log::error("ClickUpService: Exception while fetching task {$taskId}. Message: " . $e->getMessage());
            return null;
        }
    }
}
