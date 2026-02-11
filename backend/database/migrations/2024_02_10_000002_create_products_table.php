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
            $table->id();
            $table->string('name', 200); // Max 200 chars
            $table->string('slug')->unique();
            $table->string('short_description', 200); // Max 200 chars (desc field)
            $table->text('full_description')->nullable(); // productDetails
            $table->decimal('price', 8, 2); // 99999.99 max
            $table->string('tag')->nullable(); // "NEW", "BESTSELLER", or null
            $table->string('color_hex', 7)->nullable(); // Hex color

            // Product details
            $table->string('weight')->nullable(); // "14oz"
            $table->string('mill')->nullable(); // "Kaihara, Japan"
            $table->string('composition')->nullable(); // "100% Cotton"
            $table->string('construction')->nullable(); // "Right-hand twill"
            $table->string('treatment')->nullable(); // "Raw / Unwashed"
            $table->string('sanforization')->nullable(); // "Unsanforized"

            // Additional info
            $table->text('sizing_info')->nullable();
            $table->text('shipping_info')->nullable();
            $table->text('care_instructions')->nullable();

            // Inventory
            $table->json('sizes_available')->nullable(); // ["28","29","30","31","32","33","34","36","38","40"]
            $table->integer('stock_quantity')->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_featured')->default(false);

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['slug', 'is_active']);
            $table->index(['tag', 'is_active']);
            $table->index('is_featured');
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
