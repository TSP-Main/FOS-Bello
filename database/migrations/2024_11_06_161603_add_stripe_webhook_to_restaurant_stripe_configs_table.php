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
        Schema::table('restaurant_stripe_configs', function (Blueprint $table) {
            $table->text('stripe_webhook_secret')->nullable()->after('stripe_secret');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_stripe_configs', function (Blueprint $table) {
            $table->dropColumn('stripe_webhook_secret');
        });
    }
};
