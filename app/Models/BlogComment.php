<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Defaults\StatusEnum;
use App\Traits\Model\HasCrudUser;
use App\Traits\Model\HasCrudUserAt;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $blog_id
 * @property string|null $name
 * @property string|null $email
 * @property string|null $ip_address
 * @property StatusEnum $confirmed_type
 * @property string|null $comment
 * @property string|null $reply_comment
 * @property string|null $deleted_description
 * @property int|null $reply_comment_by
 * @property int|null $confirmed_by
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $deleted_by
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 * @property DateTime|null $deleted_at
 */
class BlogComment extends Model
{
    use HasFactory, HasCrudUser, HasCrudUserAt, SoftDeletes;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'blog_id',
        'name',
        'email',
        'ip_address',
        'confirmed_type', // Onay Durumu
        'comment', // Yorum
        'reply_comment', // Yorum Cevabı
        'deleted_description', // Silinme Nedeni
        'reply_comment_by', // Yoruma Cevap Verek Yetkili
        'confirmed_by', // Yorumu Onaylayan Kişi
        'created_by',
        'updated_by',
        'deleted_by',

        'created_at',
        'updated_at',
        'deleted_at',
    ];


    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'confirmed_type' => StatusEnum::class,
            'updated_at'     => 'datetime',
            'created_at'     => 'datetime',
            'deleted_at'     => 'datetime',
        ];
    }


    /**
     * @return BelongsTo
     */
    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class)->withDefault();
    }


    /**
     * @return BelongsTo
     */
    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by', 'id')->withDefault();
    }


    /**
     * @return BelongsTo
     */
    public function replyCommentBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reply_comment_by', 'id')->withDefault();
    }


    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return !empty($this->created_at) ? $this->created_at->format('d-m-Y H:i') : '';
    }
}
