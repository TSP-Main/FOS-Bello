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
        Schema::table('companies', function (Blueprint $table) {
            $table->tinyInteger('package')->nullable();
            $table->tinyInteger('plan')->nullable();
            $table->text('customer_stripe_id')->nullable();
            $table->text('payment_method_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('package');
            $table->dropColumn('plan');
            $table->dropColumn('customer_stripe_id');
            $table->dropColumn('payment_method_id');
        });
    }
};
