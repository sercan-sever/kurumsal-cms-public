<?php

declare(strict_types=1);

namespace App\Rules\Blogs;

use App\Services\Backend\Blogs\Blog\BlogService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UpdatePublishedAtRule implements ValidationRule
{
    /**
     * @var BlogService
     */
    private BlogService $blogService;


    /**
     * @param int|null $blogId
     */
    public function __construct(protected ?int $blogId)
    {
        $this->blogService = new BlogService();
    }


    /**
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     *
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
    // Blog'u veritabanından al
    $blog = $this->blogService->getModelById(id: $this->blogId);

    // Blog bulunamazsa hata
    if (!$blog) {
        $fail(__('Blog Bulunamadı !!!' ?? 'Blog Bulunamadı !!!'));
        return;
    }

    // Veritabanındaki mevcut tarih
    $currentPublishedAt = $blog->published_at;

    // Eğer yeni tarih veritabanındaki tarihten farklıysa kontrolleri uygula
    if ($value !== $currentPublishedAt->format('Y-m-d H:i') &&
        ($value < now()->format('Y-m-d H:i') || $value > now()->addDays(7)->format('Y-m-d H:i'))) {
        $fail(__('Paylaşım Tarihi şu andan önce veya 7 günden fazla olamaz !!!' ?? 'Paylaşım Tarihi şu andan önce veya 7 günden fazla olamaz !!!'));
    }
    }
}
