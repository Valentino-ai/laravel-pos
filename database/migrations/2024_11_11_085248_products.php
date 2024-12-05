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
        Schema::create('products', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            
            $table->uuid('id')->primary();                   
            $table->string('name');                   
            $table->text('description')->nullable();               
            $table->foreignUuid('size_id')->references('id')->on('sizes')->onDelete('cascade');
            $table->decimal('unit_price', 10, 2)->default(0.00);          
            $table->foreignUuid('category_id')->nullable()->constrained('categorys')->onDelete('set null'); 
            $table->string('color');                                      
            $table->foreignUuid('material_id')->nullable()->constrained('materials')->onDelete('set null'); 
            $table->timestamps();                                        
            $table->string('image_url')->nullable();                      
            $table->softDeletes();                                        
            
            $table->index(['category_id', 'size_id', 'material_id', 'color']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};