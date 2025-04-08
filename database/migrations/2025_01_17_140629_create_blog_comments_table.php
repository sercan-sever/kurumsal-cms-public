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
        Schema::create('blog_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blog_id');

            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->enum('confirmed_type', StatusEnum::values())->default(StatusEnum::PASSIVE->value)->nullable(); // Onaylanma Durumu

            $table->text('comment')->nullable();
            $table->text('reply_comment')->nullable(); // yoruma cevap

            $table->string('deleted_description')->nullable();

            $table->unsignedBigInteger('reply_comment_by')->nullable(); // yoruma cevap veren yönetici
            $table->unsignedBigInteger('confirmed_by')->nullable(); // Onaylayan Kişi
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->softDeletes('deleted_at');
            $table->timestamps();


            $table->foreign('blog_id')->references('id')->on('blogs')->cascadeOnDelete();

            $table->foreign('reply_comment_by')->references('id')->on('users'); // yoruma cevap veren yönetici
            $table->foreign('confirmed_by')->references('id')->on('users');
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
        Schema::dropIfExists('blog_comments');
    }
};
