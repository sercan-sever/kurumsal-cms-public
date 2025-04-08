<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Defaults\StatusEnum;
use App\Traits\Model\HasBlogStatusInput;
use App\Traits\Model\HasCrudUser;
use App\Traits\Model\HasCrudUserAt;
use App\Traits\Model\HasImage;
use App\Traits\Model\HasPublishedAt;
use App\Traits\Model\HasStatusInput;

use DateTime;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property int $id
 * @property string|null $image
 * @property string|null $type
 * @property int|null $sorting
 * @property StatusEnum $status
 * @property StatusEnum $comment_status
 * @property StatusEnum $subscribe_status
 * @property StatusEnum $notified_at
 * @property string|null $deleted_description
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property DateTime|null $published_at
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 * @property DateTime|null $deleted_at
 */
class Blog extends Model
{
    use HasFactory,
        HasStatusInput,
        HasBlogStatusInput,
        HasCrudUser,
        HasCrudUserAt,
        HasPublishedAt,
        HasImage,
        SoftDeletes;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'image',
        'type',
        'status',
        'comment_status',
        'sorting',
        'notified_at', // Abonelere Mail Gönderme İşlemi
        'deleted_description',
        'created_by',
        'updated_by',
        'deleted_by',
        'published_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];


    /**
     * @return array<string, string|StatusEnum>
     */
    protected function casts(): array
    {
        return [
            'status'         => StatusEnum::class,
            'comment_status' => StatusEnum::class,
            'notified_at'    => StatusEnum::class,
            'published_at'   => 'datetime',
            'created_at'     => 'datetime',
            'updated_at'     => 'datetime',
            'deleted_at'     => 'datetime',
        ];
    }


    /**
     * @return BelongsTo
     */
    public function content(): BelongsTo
    {
        $siteLangID = request(key: 'siteLangID', default: '');

        return $this->belongsTo(BlogContent::class, 'id', 'blog_id')->where('language_id', $siteLangID)->withDefault();
    }


    /**
     * @return HasMany
     */
    public function allContent(): HasMany
    {
        return $this->hasMany(BlogContent::class);
    }


    /**
     * @return BelongsTo
     */
    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class, 'language_id', 'id')->withDefault();
    }


    /**
     * @return BelongsToMany
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_category_blogs', 'blog_id', 'blog_category_id');
    }


    /**
     * @return BelongsToMany
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(BlogTag::class, 'blog_tag_blogs', 'blog_id', 'blog_tag_id');
    }


    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class, 'blog_id', 'id')
            ->where('confirmed_type', StatusEnum::ACTIVE)
            ->whereNull('deleted_at');
    }
}
