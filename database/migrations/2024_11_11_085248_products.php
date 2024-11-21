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
            $table->engine = 'InnoDB';  // Ensure the use of InnoDB for foreign keys
            
            $table->uuid('id')->primary();                               // UUID as the primary key
            $table->string('name');                                       // Type of the product (e.g., T-shirt, Bag)
            $table->text('description')->nullable();                      // Detailed description of the product                
            $table->foreignUuid('size_id')->references('id')->on('sizes')->onDelete('cascade'); // Foreign key to Sizes table
            $table->decimal('unit_price', 10, 2)->default(0.00);          // Price for this product and size
            $table->foreignUuid('category_id')->nullable()->constrained('categorys')->onDelete('set null'); // Foreign key to C
            $table->string('color');                                      // Color of the product
            $table->foreignUuid('material_id')->nullable()->constrained('materials')->onDelete('set null'); // Foreign key to Materials (UUID)
            $table->timestamps();                                         // Automatically handles created_at and updated_at
            $table->string('image_url')->nullable();                       // Image URL (only one image)
            $table->softDeletes();                                        // Soft delete capability
            
            // Index for performance improvement
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