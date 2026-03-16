<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clickup_mappings', function (Blueprint $table) {
            $table->id();
            // ربط الاستفسار/المشروع المحلي
            $table->foreignId('inquiry_id')->constrained()->onDelete('cascade'); 
            
            // بيانات ClickUp (تُخزن كنصوص لأنها تأتي من API خارجي)
            $table->string('clickup_task_id')->unique(); // المعرف الفريد للمهمة في ClickUp [cite: 9]
            $table->string('clickup_status_name')->nullable(); // الحالة الحالية (مثلاً: "In Progress") [cite: 39]
            
            // بيانات المزامنة والتحكم
            $table->timestamp('last_synced_at')->nullable(); // وقت آخر تحديث ناجح من الـ API [cite: 21]
            $table->json('raw_api_response')->nullable(); // اختياري: لتخزين آخر رد JSON لغايات الـ Debugging [cite: 32]
    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clickup_mappings');
    }
};
