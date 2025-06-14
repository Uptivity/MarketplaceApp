<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('content');
            $table->string('type')->default('info'); // info, warning, success, danger
            $table->timestamp('read_at')->nullable();
            $table->string('url')->nullable(); // URL to redirect to when notification is clicked
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
}

return new CreateNotificationsTable();
