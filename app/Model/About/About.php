<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Model\About;

use App\Model\Model;
use Carbon\Carbon;

/**
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $image
 * @property string $locale
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class About extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'about';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'title',
        'description',
        'image',
        'locale',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [
        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'image' => 'string',
        'locale' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
