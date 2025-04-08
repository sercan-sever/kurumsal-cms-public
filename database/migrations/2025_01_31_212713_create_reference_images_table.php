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
        Schema::create('reference_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reference_id');

            $table->string('image', 300)->nullable();
            $table->string('type', 10)->nullable();

            $table->bigInteger('sorting')->nullable();

            $table->timestamps();

            $table->foreign('reference_id')->references('id')->on('references')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reference_images');
    }
};
