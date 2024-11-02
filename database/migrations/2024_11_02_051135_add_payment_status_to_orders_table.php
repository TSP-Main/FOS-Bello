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
        Schema::table('orders', function (Blueprint $table) {
            $table->tinyInteger('payment_status')->default(0)->after('payment_option')->nullable();
            $table->text('stripe_payment_link')->after('payment_method_id')->nullable();
            $table->dropColumn('is_delivered');
            $table->dropColumn('is_cancelled');
            $table->string('phone')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('payment_status');
            $table->dropColumn('stripe_payment_link');
            $table->string('phone')->nullable(false)->change();
        });
    }
};
