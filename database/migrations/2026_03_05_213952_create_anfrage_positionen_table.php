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
        Schema::create('anfrage_positionen', function (Blueprint $table) {
            $table->id();

            $table->foreignId('anfrage_id')->constrained('anfragen')->onDelete('cascade');
            // Connects to 'preis_modules' table
            $table->foreignId('preis_module_id')->constrained('preis_modules')->onDelete('restrict');

            $table->string('kunden_auswahl')->nullable();
            $table->decimal('preis_zum_zeitpunkt', 10, 2);
            $table->integer('menge')->default(1);
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
