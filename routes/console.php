<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


// 2 Ayda 1 Çalışır.
// Log Dosyalarını Temizler
Schedule::command('log:clear')
    ->monthlyOn(dayOfMonth: 1, time: '23:59')
    ->skip(function () {
        return date('n') % 2 == 0;
    });


// Sitemap Oluşturur
// Haftanın İlk Günü (Pazartesi) Saat 00:00'da Sitemap Güncelleme
Schedule::command('sitemap:generate')->weeklyOn(1, '00:00');


// Abonelere Yeni Blog Yazılarının Gönderimi
// Her 6 saatte 1 kontrol edicek.
Schedule::command('notify:subscribers')->everySixHours();


// 7 günden eski job’ları temizler
Schedule::command('queue:prune-failed --hours=168')->daily();

