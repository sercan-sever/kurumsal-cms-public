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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('image', 300)->nullable();
            $table->string('type', 10)->nullable();
            $table->bigInteger('sorting')->nullable();
            $table->enum('status', StatusEnum::values())->default(StatusEnum::ACTIVE->value);
            $table->enum('comment_status', StatusEnum::values())->default(StatusEnum::PASSIVE->value);
            $table->enum('subscribe_status', StatusEnum::values())->default(StatusEnum::PASSIVE->value);

            $table->string('deleted_description')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->timestamp('published_at')->nullable(); // Paylaşım Tarihi
            $table->enum('notified_at', StatusEnum::values())->default(StatusEnum::PASSIVE->value);

            $table->softDeletes('deleted_at');
            $table->timestamps();

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
        Schema::dropIfExists('blogs');
    }
};
