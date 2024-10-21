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
            $table->string('phone')->after('address')->nullable();
            $table->string('owner_name')->after('phone')->nullable();
            $table->text('token')->nullable()->change();
            $table->bigInteger('created_by')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('owner_name');
            $table->string('token')->nullable(false)->change();
            $table->bigInteger('created_by')->nullable(false)->change();
        });
    }
};
