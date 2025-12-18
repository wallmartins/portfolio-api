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

namespace App\Model;

use Carbon\Carbon;

/**
 * @property int $id
 * @property string $social_name
 * @property string $social_url
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Social extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'social';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'social_name',
        'social_url',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [
        'id' => 'integer',
        'social_name' => 'string',
        'social_url' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
