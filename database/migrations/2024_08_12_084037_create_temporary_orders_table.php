<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTemporaryOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('temporary_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('address')->nullable();
            $table->decimal('total', 10, 2);
            $table->string('order_type');
            $table->string('payment_option');
            $table->string('status')->default('pending'); 
            $table->timestamps();
        });

        Schema::create('temporary_order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('temporary_order_id');
            $table->unsignedBigInteger('product_id');
            $table->string('product_title');
            $table->decimal('product_price', 8, 2);
            $table->integer('quantity');
            $table->decimal('sub_total', 8, 2);
            $table->string('options')->nullable();
            $table->timestamps();

            $table->foreign('temporary_order_id')->references('id')->on('temporary_orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('temporary_order_details');
        Schema::dropIfExists('temporary_orders');
    }
}

