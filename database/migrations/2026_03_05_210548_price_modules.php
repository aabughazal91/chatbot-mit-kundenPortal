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
        Schema::create('price_modules', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('label_de');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2); // السعر الأساسي لهذه الميزة
            
            $table->string('category');      // لتصنيف الأسئلة (Backend, Frontend, API)
            $table->boolean('is_active'); // لتعطيل ميزة مؤقتاً دون حذفها
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
