<?php

use App\Enums\Defaults\StatusEnum;
use App\Enums\Pages\Section\PageSectionEnum;
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
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('page_id')->nullable();

            $table->enum('section_type', PageSectionEnum::values())->default(PageSectionEnum::DYNAMIC->value)->nullable();

            $table->string('title')->nullable();
            $table->string('slug', 300)->nullable();

            $table->string('image', 300)->nullable();
            $table->string('type', 10)->nullable();
            $table->string('other_image', 300)->nullable();
            $table->string('other_type', 10)->nullable();

            $table->bigInteger('limit')->nullable();
            $table->bigInteger('sorting')->nullable();

            $table->enum('status', StatusEnum::values())->default(StatusEnum::ACTIVE->value);
            $table->enum('default', StatusEnum::values())->default(StatusEnum::PASSIVE->value);

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->string('deleted_description')->nullable();

            $table->softDeletes('deleted_at');
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');

            $table->foreign('page_id')->references('id')->on('pages')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
