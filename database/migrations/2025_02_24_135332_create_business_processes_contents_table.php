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
        Schema::create('business_processes_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_processes_id');
            $table->unsignedBigInteger('language_id');

            $table->string('header')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();

            $table->timestamps();

            $table->foreign('business_processes_id')->references('id')->on('business_processes')->cascadeOnDelete();
            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_processes_contents');
    }
};
