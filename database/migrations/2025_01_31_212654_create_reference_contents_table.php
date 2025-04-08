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
        Schema::create('reference_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reference_id');
            $table->unsignedBigInteger('language_id');

            $table->string('title')->nullable();
            $table->string('slug', 300)->nullable();

            $table->text('description')->nullable();
            $table->text('short_description')->nullable();

            $table->string('meta_keywords', 150)->nullable();
            $table->string('meta_descriptions', 160)->nullable();

            $table->timestamps();

            $table->foreign('reference_id')->references('id')->on('references')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reference_contents');
    }
};
