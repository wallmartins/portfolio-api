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
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Relations\BelongsToMany;
use Hyperf\Database\Model\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $image
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection<int, ProjectsTranslation> $translations
 * @property Collection<int, Tech> $techs
 */
class Project extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'projects';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'name',
        'slug',
        'image',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [
        'id' => 'integer',
        'name' => 'string',
        'slug' => 'string',
        'image' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(ProjectsTranslation::class);
    }

    public function techs(): BelongsToMany
    {
        return $this->belongsToMany(
            Tech::class,
            'project_tech',
            'project_id',
            'tech_id'
        );
    }
}
