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

namespace App\Model\Blog;

use App\Model\Model;
use App\Model\Tech\Tech;
use Carbon\Carbon;
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Relations\BelongsToMany;
use Hyperf\Database\Model\Relations\HasMany;

/**
 * @property int $id
 * @property string $slug
 * @property string $image
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection<int, PostsTranslation> $translations
 * @property Collection<int, Tech> $techs
 */
class Post extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'posts';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'slug',
        'image',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [
        'id' => 'integer',
        'slug' => 'string',
        'image' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(PostsTranslation::class);
    }

    public function techs(): BelongsToMany
    {
        return $this->belongsToMany(
            Tech::class,
            'post_tech',
            'post_id',
            'tech_id'
        );
    }
}
