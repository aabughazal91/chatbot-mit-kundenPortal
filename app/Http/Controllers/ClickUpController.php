<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use App\Models\ClickUpMapping;
use App\Services\ClickUpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClickUpController extends Controller
{
    protected ClickUpService $clickUpService;

    public function __construct(ClickUpService $clickUpService)
    {
        $this->clickUpService = $clickUpService;
    }

    /**
     * دالة الربط (يستخدمها الأدمن فقط)
     */
    public function assignTask(Request $request)
    {
        // التحقق من صحة البيانات
        $request->validate([
            'inquiry_id' => ['required', 'exists:inquiries,id'],
            'clickup_task_id' => ['required', 'string'],
        ]);

        $inquiryId = $request->input('inquiry_id');
        $taskId = ltrim($request->input('clickup_task_id'), '#'); // إزالة الهاشتاج إذا كان موجوداً

        // جلب الحالة لأول مرة عن طريق السيرفر للتأكد من صحة رقم التاسك
        $taskData = $this->clickUpService->getTaskStatus($taskId);

        if (!$taskData) {
            // معالجة الأخطاء (IHK requirement)
            return back()->withErrors([
                'clickup_error' => 'Could not fetch task from ClickUp. Please verify the Task ID and your internet connection.'
            ]);
        }

        // إنشاء أو تحديث السجل في جدول clickup_mappings
        ClickUpMapping::updateOrCreate(
            ['inquiry_id' => $inquiryId],
            [
                'clickup_task_id' => $taskId,
                'clickup_status_name' => $taskData['status_name'],
                'raw_api_response' => $taskData['raw_response'],
                'last_synced_at' => now(),
            ]
        );

        return back()->with('success', 'ClickUp Task successfully linked and status updated!');
    }

    /**
     * دالة المزامنة اليدوية أو التلقائية (Scheduler)
     */
    public function syncStatus()
    {
        $mappings = ClickUpMapping::all();
        $syncedCount = 0;
        $failedCount = 0;

        foreach ($mappings as $mapping) {
            $taskData = $this->clickUpService->getTaskStatus($mapping->clickup_task_id);

            if ($taskData) {
                $mapping->update([
                    'clickup_status_name' => $taskData['status_name'],
                    'raw_api_response' => $taskData['raw_response'],
                    'last_synced_at' => now(),
                ]);
                $syncedCount++;
            } else {
                // إذا فشل الاتصال بواحدة من المهام، نستمر مع الباقي ونسجل الخطأ
                Log::warning("ClickUpController: Failed to sync task ID {$mapping->clickup_task_id} for inquiry {$mapping->inquiry_id}");
                $failedCount++;
            }
        }

        // في حال تم الاستدعاء عبر متصفح أو API يدوي، نرجع نتيجة
        if (request()->wantsJson()) {
            return response()->json([
                'message' => 'Sync completed',
                'synced' => $syncedCount,
                'failed' => $failedCount,
            ]);
        }

        return back()->with('success', "ClickUp sync completed: {$syncedCount} updated, {$failedCount} failed.");
    }
}
