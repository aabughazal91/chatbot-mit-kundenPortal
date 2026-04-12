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
        Schema::create('preis_modules', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('bezeichnung_de');
            $table->text('beschreibung')->nullable();
            $table->decimal('preis', 10, 2);
            $table->string('typ')->default('boolean');
            $table->json('optionen')->nullable();
            $table->string('kategorie');
            $table->boolean('ist_aktiv')->default(true);
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
