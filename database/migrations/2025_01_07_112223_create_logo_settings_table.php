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
        Schema::create('logo_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('setting_id');

            // Favicon
            $table->string('favicon', 300)->nullable();
            $table->string('favicon_type', 10)->nullable();

            // Header
            $table->string('header_white', 300)->nullable();
            $table->string('header_white_type', 10)->nullable();

            $table->string('header_dark', 300)->nullable();
            $table->string('header_dark_type', 10)->nullable();

            // Footer
            $table->string('footer_white', 300)->nullable();
            $table->string('footer_white_type', 10)->nullable();

            $table->string('footer_dark', 300)->nullable();
            $table->string('footer_dark_type', 10)->nullable();

            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            $table->foreign('setting_id')->references('id')->on('settings')->cascadeOnDelete();
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logo_settings');
    }
};
