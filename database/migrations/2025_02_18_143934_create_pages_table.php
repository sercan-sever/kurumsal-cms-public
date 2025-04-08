<?php

use App\Enums\Defaults\StatusEnum;
use App\Enums\Pages\Menu\PageMenuEnum;
use App\Enums\Pages\Page\SubPageDesignEnum;
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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('top_page')->nullable();

            $table->string('image', 300)->nullable();
            $table->string('type', 10)->nullable();

            $table->bigInteger('sorting')->nullable();

            $table->enum('menu', PageMenuEnum::values())->default(PageMenuEnum::NONE->value);
            $table->enum('design', SubPageDesignEnum::values())->default(SubPageDesignEnum::NONE->value);
            $table->enum('status', StatusEnum::values())->default(StatusEnum::ACTIVE->value);
            $table->enum('breadcrumb', StatusEnum::values())->default(StatusEnum::ACTIVE->value);

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->string('deleted_description')->nullable();

            $table->softDeletes('deleted_at');
            $table->timestamps();

            $table->foreign('top_page')->references('id')->on('pages');

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
        Schema::dropIfExists('pages');
    }
};
