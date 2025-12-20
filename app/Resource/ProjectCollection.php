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

namespace App\Resource;

use App\Model\Project;

/**
 * @extends ResourceCollection<Project>
 */
class ProjectCollection extends ResourceCollection
{
    public function toArray(): array
    {
        return [
            'data' => $this->collection->map(function ($project) {
                return ProjectPublicResource::make($project)->toArray();
            })->toArray(),
            'meta' => $this->paginator && [
                'current_page' => $this->paginator->currentPage(),
                'per_page' => $this->paginator->perPage(),
                'count' => $this->paginator->count(),
            ],
        ];
    }
}
