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
use Hyperf\Database\Model\Relations\BelongsToMany;
use Hyperf\Database\Model\Relations\HasMany;

/**
 * @property int $id
 * @property string $title
 * @property string $subtitle
 * @property string $slug
 * @property string $image
 * @property Carbon $created_at
 * @property Carbon $updated_at
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
        'title',
        'subtitle',
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

    public function translationsByLocale(string $locale): ?object
    {
        return $this->translations()->where('locale', $locale)->first();
    }
}
