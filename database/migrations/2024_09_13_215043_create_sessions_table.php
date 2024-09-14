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
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('session_id')->primary();
            $table->string('country')->nullable();          // Country Name
            $table->string('ip_address')->nullable();       //  IP Address Device
            $table->string('device')->nullable();           //  Device (Merk & Type)
            $table->string('platform')->nullable();         //  Operating System
            $table->string('browser')->nullable();          //  Browser Name
            $table->integer('total_page')->default(1);
            $table->timestamp('first_active_at');
            $table->timestamp('last_active_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
