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

namespace App\Model\Project;

use App\Model\Model;
use Carbon\Carbon;

/**
 * @property int $id
 * @property int $project_id
 * @property string $title
 * @property string $content
 * @property string $locale
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ProjectsTranslation extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'projects_translations';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'project_id',
        'title',
        'content',
        'locale',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [
        'id' => 'integer',
        'project_id' => 'integer',
        'title' => 'string',
        'content' => 'string',
        'locale' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
