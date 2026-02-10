<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_image_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "Spring 2026", "Black Friday Sale"
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(false); // Only one can be active
            $table->integer('slideshow_speed')->default(3); // Seconds per slide
            $table->timestamps();
        });

        // Add campaign_id to hero_images
        Schema::table('hero_images', function (Blueprint $table) {
            $table->foreignId('campaign_id')->nullable()->after('id')->constrained('hero_image_campaigns')->onDelete('cascade');
        });

        // Create default campaign and assign existing images
        DB::table('hero_image_campaigns')->insert([
            'name' => 'Default Campaign',
            'slug' => 'default',
            'description' => 'Year-round hero images',
            'is_active' => true,
            'slideshow_speed' => 3,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $defaultCampaignId = DB::table('hero_image_campaigns')->where('slug', 'default')->value('id');
        DB::table('hero_images')->update(['campaign_id' => $defaultCampaignId]);
    }

    public function down(): void
    {
        Schema::table('hero_images', function (Blueprint $table) {
            $table->dropForeign(['campaign_id']);
            $table->dropColumn('campaign_id');
        });

        Schema::dropIfExists('hero_image_campaigns');
    }
};
