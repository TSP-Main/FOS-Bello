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
        Schema::table('restaurant_schedules', function (Blueprint $table) {
            $table->time('delivery_start_time')->after('closing_time')->nullable();
            $table->time('collection_start_time')->after('delivery_start_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_schedules', function (Blueprint $table) {
            $table->dropColumn('delivery_start_time');
            $table->dropColumn('collection_start_time');
        });
    }
};
