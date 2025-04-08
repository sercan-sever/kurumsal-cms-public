<?php

namespace App\Console\Commands;

use App\Services\Frontend\Sitemap\SitemapService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Güncellenmiş sitemap.xml dosyası oluşturur';

    /**
     * Execute the console command.
     */
    public function handle(SitemapService $sitemapService)
    {
        try {
            $sitemapService->generateSitemap();

            $this->info('Sitemap başarıyla oluşturuldu.');
        } catch (\Exception $exception) {
            Log::error(message: 'GenerateSitemap ( handle ) Bir Hata Oluştu !!!', context: [$exception->getMessage()]);
        }
    }
}
