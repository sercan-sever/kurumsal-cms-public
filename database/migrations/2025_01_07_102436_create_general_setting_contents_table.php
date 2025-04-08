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
        Schema::create('general_setting_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('general_setting_id');
            $table->unsignedBigInteger('language_id');

            $table->string('title', 300)->nullable();
            $table->string('meta_keywords', 150)->nullable();
            $table->string('meta_descriptions', 160)->nullable();
            $table->timestamps();

            $table->foreign('general_setting_id')->references('id')->on('general_settings')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_setting_contents');
    }
};
