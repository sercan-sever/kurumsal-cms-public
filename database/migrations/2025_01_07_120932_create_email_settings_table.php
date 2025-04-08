<?php

use App\Enums\Emails\EmailEncryptionEnum;
use App\Enums\Emails\EmailEngineEnum;
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
        Schema::create('email_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('setting_id');

            $table->string('notification_email')->nullable(); // Bildirimlerin gönderileceği e-posta adresi
            $table->string('sender_email')->nullable();       // Gönderici e-posta adresi
            $table->string('subject')->nullable();      // E-posta başlığı

            $table->enum('engine', EmailEngineEnum::values())->default(EmailEngineEnum::SMTP->value); // E-posta motoru
            $table->string('host')->nullable();          // SMTP sunucu adı
            $table->unsignedSmallInteger('port')->default(25); // SMTP portu (ör. 25)
            $table->enum('encryption', EmailEncryptionEnum::values())->default(EmailEncryptionEnum::SSL->value); // Şifreleme türü

            $table->string('username')->nullable();      // SMTP kullanıcı adı
            $table->longText('password')->nullable();      // SMTP parolası

            $table->unsignedSmallInteger('timeout')->default(60); // SMTP zaman aşımı süresi (saniye cinsinden)

            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('setting_id')->references('id')->on('settings')->cascadeOnDelete();
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_settings');
    }
};
