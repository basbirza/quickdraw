<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_images', function (Blueprint $table) {
            $table->id();
            $table->string('image_path'); // Path to image in storage
            $table->string('alt_text')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert existing hero images
        DB::table('hero_images')->insert([
            ['image_path' => 'hero/quickdraw_branded_lower_1.png', 'alt_text' => 'Quickdraw Pressing Co.', 'sort_order' => 0, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['image_path' => 'hero/quickdraw_branded_lower_2.png', 'alt_text' => 'Quickdraw Pressing Co.', 'sort_order' => 1, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
            ['image_path' => 'hero/quickdraw_branded_lower_3.png', 'alt_text' => 'Quickdraw Pressing Co.', 'sort_order' => 2, 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_images');
    }
};
