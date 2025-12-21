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
use Carbon\Carbon;
use Hyperf\Database\Model\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $post_id
 * @property string $locale
 * @property string $title
 * @property string $subtitle
 * @property string $content
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class PostsTranslation extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'posts_translations';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'post_id',
        'locale',
        'title',
        'subtitle',
        'content',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [
        'id' => 'integer',
        'post_id' => 'integer',
        'locale' => 'string',
        'title' => 'string',
        'subtitle' => 'string',
        'content' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
