<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Setting identifier
            $table->text('value')->nullable(); // Setting value
            $table->string('type')->default('text'); // text, textarea, number, boolean, image
            $table->string('group')->default('general'); // Group settings logically
            $table->string('label'); // Display name
            $table->text('description')->nullable(); // Helper text
            $table->timestamps();
        });

        // Insert default settings
        DB::table('site_settings')->insert([
            // Store Info
            ['key' => 'store_name', 'value' => 'Quickdraw Pressing Co.', 'type' => 'text', 'group' => 'store', 'label' => 'Store Name', 'description' => 'Your store name'],
            ['key' => 'store_email', 'value' => 'support@quickdrawpressing.co', 'type' => 'text', 'group' => 'store', 'label' => 'Support Email', 'description' => 'Customer support email'],
            ['key' => 'store_phone', 'value' => '+31 20 123 4567', 'type' => 'text', 'group' => 'store', 'label' => 'Phone Number', 'description' => 'Contact phone number'],

            // Announcement
            ['key' => 'announcement_text', 'value' => 'FREE SHIPPING ON ORDERS ABOVE €150 — SUBSCRIBE & GET 10% OFF', 'type' => 'text', 'group' => 'homepage', 'label' => 'Announcement Bar Text', 'description' => 'Text shown in top announcement bar'],
            ['key' => 'announcement_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'homepage', 'label' => 'Show Announcement Bar', 'description' => 'Display the announcement bar'],

            // Shipping
            ['key' => 'free_shipping_threshold', 'value' => '150', 'type' => 'number', 'group' => 'shipping', 'label' => 'Free Shipping Threshold (€)', 'description' => 'Minimum order value for free shipping'],
            ['key' => 'standard_shipping_cost', 'value' => '9.95', 'type' => 'number', 'group' => 'shipping', 'label' => 'Standard Shipping Cost (€)', 'description' => 'Flat rate shipping cost'],

            // Social Media
            ['key' => 'instagram_url', 'value' => 'https://instagram.com/quickdrawpressing', 'type' => 'text', 'group' => 'social', 'label' => 'Instagram URL', 'description' => 'Your Instagram profile'],
            ['key' => 'tiktok_url', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'TikTok URL', 'description' => 'Your TikTok profile'],
            ['key' => 'pinterest_url', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'Pinterest URL', 'description' => 'Your Pinterest profile'],

            // Homepage Content
            ['key' => 'hero_button_text', 'value' => 'SHOP THE COLLECTION', 'type' => 'text', 'group' => 'homepage', 'label' => 'Hero Button Text', 'description' => 'Text on hero section button'],
            ['key' => 'brand_story_title', 'value' => 'Our Story', 'type' => 'text', 'group' => 'homepage', 'label' => 'Brand Story Title', 'description' => 'Brand story section title'],
            ['key' => 'brand_story_text', 'value' => 'Born from a reverence for Japanese craftsmanship and American frontier spirit, Quickdraw Pressing Co. creates selvedge denim and Americana wear built for a lifetime of stories.', 'type' => 'textarea', 'group' => 'homepage', 'label' => 'Brand Story Text', 'description' => 'Main brand story paragraph'],

            // Newsletter
            ['key' => 'newsletter_discount', 'value' => '10', 'type' => 'number', 'group' => 'marketing', 'label' => 'Newsletter Discount (%)', 'description' => 'Discount percentage for newsletter signup'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
