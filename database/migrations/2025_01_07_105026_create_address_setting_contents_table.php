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
        Schema::create('address_setting_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('address_setting_id');
            $table->unsignedBigInteger('language_id');

            // E-Mail
            $table->string('email_title_one')->nullable();
            $table->string('email_address_one')->nullable();

            $table->string('email_title_two')->nullable();
            $table->string('email_address_two')->nullable();

            // Phone
            $table->string('phone_title_one')->nullable();
            $table->string('phone_number_one')->nullable();

            $table->string('phone_title_two')->nullable();
            $table->string('phone_number_two')->nullable();

            // Address
            $table->string('address_title_one')->nullable();
            $table->text('address_content_one')->nullable();
            $table->text('address_iframe_one')->nullable();

            $table->string('address_title_two')->nullable();
            $table->text('address_content_two')->nullable();
            $table->text('address_iframe_two')->nullable();


            $table->timestamps();

            $table->foreign('address_setting_id')->references('id')->on('address_settings')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('address_setting_contents');
    }
};
