<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
      
	  Schema::table('users', function (Blueprint $table) {
        $table->enum('role', ['buyer', 'seller', 'admin', 'support', 'management'])->default('buyer');
        $table->timestamp('age_verified_at')->nullable();
        $table->enum('verification_status', ['pending', 'verified', 'failed'])->default('pending');
    });
	   
	   
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
       Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['role', 'age_verified_at', 'verification_status']);
    });
    }
};
