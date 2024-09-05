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
            $table->dropColumn('address');
            $table->dropColumn('radius');
            $table->dropColumn('coordinates');
            $table->string('address')->after('timezone')->nullable();
            $table->string('apartment')->after('address')->nullable();
            $table->string('city')->after('apartment')->nullable();
            $table->string('postcode')->after('city')->nullable();
            $table->decimal('radius', 8, 2)->after('postcode')->nullable();
            $table->decimal('latitude', 10, 7)->after('radius')->nullable();
            $table->decimal('longitude', 10, 7)->after('latitude')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            
            $table->dropColumn(['address', 'apartment', 'city', 'postcode', 'radius', 'latitude', 'longitude']);
        });
    }
};
