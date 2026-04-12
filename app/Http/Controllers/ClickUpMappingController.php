<?php

namespace App\Http\Controllers;

use App\Services\ClickUpService;
use Illuminate\Http\Request;

class ClickUpMappingController extends Controller
{
    protected $clickUpService;

    public function __construct(ClickUpService $service)
    {
        $this->clickUpService = $service;
    }

    /**
     * ربط طلب (Inquiry) بمهمة ClickUp (Task)
     * @param Request $request
     */
    public function store(Request $request)
    {
        // 1. Validation: التأكد من وجود anfrage_id و clickup_aufgabe_id
        // 2. إنشاء سجل في جدول clickup_mappings
        // 3. استدعاء الخدمة لجلب الحالة فوراً (Initial Sync)
        // 4. إرجاع استجابة بنجاح العملية أو فشلها
    }

    /**
     * تحديث الحالة يدوياً من لوحة التحكم (Manual Sync)
     * @param int $mappingId
     */
    public function syncNow(int $mappingId)
    {
        // 1. العثور على سجل الـ Mapping
        // 2. طلب البيانات الجديدة من ClickUpService
        // 3. تحديث قاعدة البيانات (Status, zuletzt_synchronisiert_am)
    }
}
