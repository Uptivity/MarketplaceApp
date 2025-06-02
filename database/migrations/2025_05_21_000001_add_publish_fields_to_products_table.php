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
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_published')->default(true)->after('low_stock_threshold');
            $table->timestamp('publish_at')->nullable()->after('is_published');
            $table->timestamp('last_low_stock_notification')->nullable()->after('publish_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_published', 'publish_at', 'last_low_stock_notification']);
        });
    }
};
