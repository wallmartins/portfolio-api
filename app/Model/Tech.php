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
 * @property string $slug
 * @property string $name
 * @property Carbon $start_date
 * @property string $category
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Tech extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'techs';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'slug',
        'name',
        'start_date',
        'category',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [
        'id' => 'integer',
        'slug' => 'string',
        'name' => 'string',
        'start_date' => 'datetime',
        'category' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
