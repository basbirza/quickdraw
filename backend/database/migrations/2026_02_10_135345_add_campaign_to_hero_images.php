<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hero_images', function (Blueprint $table) {
            $table->string('campaign')->default('default')->after('image_path');
        });

        // Add active campaign setting
        DB::table('site_settings')->insert([
            'key' => 'active_hero_campaign',
            'value' => 'default',
            'type' => 'text',
            'group' => 'homepage',
            'label' => 'Active Hero Campaign',
            'description' => 'Which hero image campaign to display (default, spring, summer, sale, etc.)',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Set existing images to default campaign
        DB::table('hero_images')->update(['campaign' => 'default']);
    }

    public function down(): void
    {
        Schema::table('hero_images', function (Blueprint $table) {
            $table->dropColumn('campaign');
        });

        DB::table('site_settings')->where('key', 'active_hero_campaign')->delete();
    }
};
