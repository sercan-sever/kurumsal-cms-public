<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'log:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Storage/logs Dizinindeki Tüm Günlük Dosyalarını Temizle';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $logPath = storage_path('logs');

        if (File::exists($logPath)) {
            $files = File::glob($logPath . '/*.log');

            foreach ($files as $file) {
                File::delete($file);
            }

            $this->info('Kayıtlar Başarıyla Temizlendi.');
        } else {
            $this->info('Hiçbir Günlük Dosyası Bulunamadı.');
        }
    }
}
