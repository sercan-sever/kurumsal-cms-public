<?php

use App\Enums\Defaults\StatusEnum;
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
        Schema::create('translation_contents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('translation_id');
            $table->string('group', 255)->nullable();
            $table->string('key', 500)->nullable();
            $table->text('value')->nullable();
            $table->enum('default', StatusEnum::values())->default(StatusEnum::PASSIVE->value);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();

            $table->foreign('translation_id')->references('id')->on('translations')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translation_contents');
    }
};
