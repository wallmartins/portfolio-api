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

use Hyperf\Database\Model\Collection;

abstract class ResourceCollection
{
    public function __construct(
        protected Collection $collection
    ) {
    }

    /**
     * Transform the resource collection into an array.
     */
    abstract public function toArray(): array;

    /**
     * Create a new resource collection instance.
     */
    public static function make(Collection $collection): static
    {
        return new static($collection);
    }
}
