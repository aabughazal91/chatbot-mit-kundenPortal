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
        Schema::create('chatbot_angebote', function (Blueprint $table) {
            $table->id();
            $table->string('angebot_nummer')->unique(); 
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); 
            $table->json('antworten');  
            $table->decimal('gesamtsumme_schaetzung', 10, 2);
            $table->string('status')->default('pending'); 
            $table->string('pdf_pfad')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_angebote');
    }
};
