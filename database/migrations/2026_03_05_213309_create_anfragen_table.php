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
       Schema::create('anfragen', function (Blueprint $table) {
            $table->id();
            $table->string('angebot_nummer')->unique(); 
            $table->string('sessions_id')->nullable(); 
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); 
            $table->decimal('geschätzter_gesamtpreis', 10, 2); 
            $table->string('pdf_pfad')->nullable(); 
            $table->string('status')->default('offen'); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anfragen');
    }
};
