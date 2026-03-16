<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// تنفيذ أمر مزامنة ClickUp كل ساعة
Schedule::command('clickup:sync-status')->hourly();
//ملاحظة للتوثيق: اذكر أنك اخترت hourly() لتجنب حظر الـ API (Rate Limiting) ولأن حالة المشاريع لا تتغير بسرعة تستدعي التحديث كل دقيقة