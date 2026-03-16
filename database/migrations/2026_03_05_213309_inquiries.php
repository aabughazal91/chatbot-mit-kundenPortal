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
       Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('quote_number')->unique(); // التنسيق الذي اخترته BOT-YYYYMMDD...
            $table->string('session_id')->nullable(); 
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); 
            $table->decimal('total_estimated_price', 10, 2); // [cite: 6]
            $table->string('pdf_path')->nullable(); // [cite: 7]
            $table->string('status')->default('pending'); // 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
