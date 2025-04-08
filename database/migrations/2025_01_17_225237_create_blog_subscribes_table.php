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
        Schema::create('blog_subscribes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('language_id')->nullable();

            $table->string('email')->unique();
            $table->string('ip_address', 45)->nullable();
            $table->string('deleted_description')->nullable();
            $table->enum('status', StatusEnum::values())->default(StatusEnum::ACTIVE->value)->nullable();

            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->softDeletes('deleted_at');
            $table->timestamps();

            $table->foreign('language_id')->references('id')->on('languages')->cascadeOnDelete();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_subscribes');
    }
};
