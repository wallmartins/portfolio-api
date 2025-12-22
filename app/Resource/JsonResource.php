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

use App\Contracts\Arrayable;
use Hyperf\DbConnection\Model\Model;

/**
 * @template TModel of Model
 */
abstract class JsonResource implements Arrayable
{
    /**
     * @param TModel $resource
     */
    public function __construct(
        protected Model $resource
    ) {
    }

    /**
     * Transform the resource into an array.
     */
    abstract public function toArray(): array;

    /**
     * Create a new resource instance.
     *
     * @param TModel $resource
     */
    public static function make(Model $resource): static
    {
        return new static($resource);
    }
}
