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

namespace App\Resource\Blog;

use App\Model\Blog\Post;
use App\Resource\ResourceCollection;

/**
 * @extends ResourceCollection<Post>
 */
class PostCollection extends ResourceCollection
{
    public function toArray(): array
    {
        return [
            'data' => $this->collection->map(function ($post) {
                return PostPublicResource::make($post)->toArray();
            })->toArray(),
            'meta' => $this->paginator && [
                'current_page' => $this->paginator->currentPage(),
                'per_page' => $this->paginator->perPage(),
                'count' => $this->collection->count(),
            ],
        ];
    }
}
