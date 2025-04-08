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
        Schema::create('banner_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banner_id');
            $table->unsignedBigInteger('language_id');

            $table->string('title')->nullable();
            $table->text('description')->nullable();

            $table->string('button_title')->nullable();
            $table->text('url')->nullable(); // max:600 olacak Hata almamak için uzun bırakılacak.

            $table->timestamps();

            $table->foreign('banner_id')->references('id')->on('banners')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_contents');
    }
};
