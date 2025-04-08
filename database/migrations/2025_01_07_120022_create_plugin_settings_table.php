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
        Schema::create('plugin_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('setting_id');
            $table->string('recaptcha_site_key')->nullable(); // ReCAPTCHA Site Key
            $table->string('recaptcha_secret_key')->nullable(); // ReCAPTCHA Secret Key
            $table->string('analytics_four')->nullable(); // Google Analytics 4

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
        Schema::dropIfExists('plugin_settings');
    }
};
