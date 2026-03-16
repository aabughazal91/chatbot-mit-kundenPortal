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
        Schema::create('chatbot_quotes', function (Blueprint $table) {
           $table->id();
            $table->string('quote_number')->unique(); // للرجوع إليه في الـ PDF [cite: 7]
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // ربط العميل 
            $table->json('answers'); // تفاصيل الاختيارات 
            $table->decimal('total_estimate', 10, 2); // رفع الخانات لـ 10 احتياطاً
            $table->string('status')->default('pending'); // لإدارة الطلب من الأدمن [cite: 10]
            $table->string('pdf_path')->nullable(); // لتخزين مسار الملف المولد [cite: 7]
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_quotes');
    }
};
