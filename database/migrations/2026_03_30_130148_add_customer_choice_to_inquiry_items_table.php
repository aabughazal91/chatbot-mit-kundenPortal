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
        Schema::table('inquiry_items', function (Blueprint $table) {
            $table->string('customer_choice')->nullable()->after('price_module_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inquiry_items', function (Blueprint $table) {
            $table->dropColumn('customer_choice');
        });
    }
};
