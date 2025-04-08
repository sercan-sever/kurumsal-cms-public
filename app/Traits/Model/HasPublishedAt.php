<?php

declare(strict_types=1);

namespace App\Traits\Model;

use Carbon\Carbon;

trait HasPublishedAt
{
    /**
     * @return string
     */
    public function getPublishedAtHtml(): string
    {
        // Yayınlanma tarihini al ve Carbon nesnesine çevir
        $publishedAt = $this->published_at instanceof Carbon
            ? $this->published_at
            : Carbon::parse($this->published_at);

        $now = now();

        // Eğer yayınlanma tarihi geçmişse
        if (is_null($publishedAt) || $publishedAt <= $now) {
            return '<span class="badge badge-success p-3">Yayınlandı</span>';
        }

        // Kalan süreyi Carbon'un `diff` fonksiyonu ile hesapla
        $diff = $publishedAt->diff($now);

        // Gün, saat, dakika ve saniye bilgilerini birleştir
        $formattedRemaining = collect([
            $diff->d > 0 ? $diff->d . ' gün' : null,
            $diff->d > 0 || $diff->h > 0 ? $diff->h . ' saat' : null,
            $diff->d === 0 ? $diff->i . ' dakika' : null,
            $diff->d === 0 ? $diff->s . ' saniye' : null,
        ])->filter()->implode(' ');

        return '<span class="badge badge-info p-3">Yayına ' . $formattedRemaining . ' kaldı</span>';
    }


    /**
     * @return string
     */
    public function getPublishedAt(): string
    {
        return !empty($this->published_at) ? $this->published_at->format('Y-m-d H:i') : now()->format('Y-m-d H:i');
    }


    /**
     * @return string
     */
    public function getPublishedAtFrontend(): string
    {
        return !empty($this->published_at) ? $this->published_at->format('d / m / Y') : '';
    }


    /**
     * @return bool
     */
    public function checkPublishedAt(): bool
    {
        if (empty($this->published_at)) {
            return false;
        }

        return $this->published_at->greaterThan(now());
    }
}
