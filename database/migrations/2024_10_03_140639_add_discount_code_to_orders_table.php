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
            $table->decimal('original_bill', 8, 2)->after('total');
            $table->string('discount_code')->after('original_bill')->nullable();
            $table->decimal('discount_amount', 8, 2)->after('discount_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('original_bill');
            $table->dropColumn('discount_code');
            $table->dropColumn('discount_amount');
        });
    }
};
