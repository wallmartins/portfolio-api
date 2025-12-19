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
use Hyperf\DbConnection\Model\Model;

/**
 * @template TModel of Model
 */
abstract class ResourceCollection
{
    /**
     * @param Collection<int, TModel> $collection
     */
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
     *
     * @param Collection<int, TModel> $collection
     */
    public static function make(Collection $collection): static
    {
        return new static($collection);
    }
}
