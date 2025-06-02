<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('site_settings')->insert([
            ['key' => 'navbar_color', 'value' => '#3b82f6', 'created_at' => now(), 'updated_at' => now()], // Blue color
            ['key' => 'button_color', 'value' => '#3b82f6', 'created_at' => now(), 'updated_at' => now()], 
            ['key' => 'accent_color', 'value' => '#10b981', 'created_at' => now(), 'updated_at' => now()], // Green color
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
