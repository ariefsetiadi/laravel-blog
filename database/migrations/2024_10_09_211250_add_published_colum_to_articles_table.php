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
        Schema::table('articles', function (Blueprint $table) {
            $table->smallInteger('status')->default(3)->change();     //  0: Draft, 1: On Review, 2: Revise, 3: Publish
            $table->bigInteger('published_by')->nullable()->unsigned()->after('updated_by');
            $table->timestamp('published_at')->nullable()->after('published_by');

            $table->foreign('published_by')->references('id')->on('users')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->dropForeign('published_by');
            $table->dropColumn(['published_by', 'published_at']);
            $table->boolean('status')->default(true);
        });
    }
};
