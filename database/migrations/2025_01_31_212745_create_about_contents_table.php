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
        Schema::create('about_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('about_id');
            $table->unsignedBigInteger('language_id');

            $table->string('title')->nullable();
            $table->string('slug', 300)->nullable();

            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->text('mission')->nullable();
            $table->text('vision')->nullable();

            $table->timestamps();

            $table->foreign('about_id')->references('id')->on('abouts')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_contents');
    }
};
