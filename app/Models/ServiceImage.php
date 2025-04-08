<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\Model\HasImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $service_id
 * @property string|null $image
 * @property string|null $type
 * @property int|null $sorting
 */
class ServiceImage extends Model
{
    use HasFactory, HasImage;


    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'service_id',
        'image',
        'type',
        'sorting',
    ];
}
