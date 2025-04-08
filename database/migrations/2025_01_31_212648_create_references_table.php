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
        Schema::create('references', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('brand_id')->nullable();

            $table->string('image', 300)->nullable();
            $table->string('type', 10)->nullable();

            $table->string('other_image', 300)->nullable();
            $table->string('other_type', 10)->nullable();

            $table->bigInteger('sorting')->nullable();
            $table->enum('status', StatusEnum::values())->default(StatusEnum::ACTIVE->value);

            $table->string('deleted_description')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamp('completion_date')->nullable(); // Tamamlanma Tarihi
            $table->softDeletes('deleted_at');
            $table->timestamps();

            $table->foreign('brand_id')->references('id')->on('brands')->cascadeOnDelete();

            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('references');
    }
};
