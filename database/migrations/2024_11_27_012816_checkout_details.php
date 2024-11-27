<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('checkout_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('checkout_id')->index(); // Foreign key to checkouts table
            $table->uuid('product_id')->index(); // Foreign key to products table
            $table->integer('quantity'); // Quantity purchased
            $table->decimal('unit_price', 10, 2); // Price per unit
            $table->decimal('subtotal', 10, 2); // quantity * unit_price
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('checkout_id')->references('id')->on('checkouts')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checkout_details');
    }
};
