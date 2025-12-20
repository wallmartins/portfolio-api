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

use Hyperf\Contract\PaginatorInterface;
use Hyperf\Database\Model\Collection;
use Hyperf\DbConnection\Model\Model;

/**
 * @template TModel of Model
 */
abstract class ResourceCollection
{
    /**
     * @var Collection<int, TModel>
     */
    protected Collection $collection;

    protected ?PaginatorInterface $paginator = null;

    /**
     * @param Collection<int, TModel>|PaginatorInterface $resource
     */
    public function __construct(Collection|PaginatorInterface $resource)
    {
        if ($resource instanceof PaginatorInterface) {
            $this->paginator = $resource;
            $this->collection = $resource->getCollection();
            return;
        }

        $this->collection = $resource;
    }

    /**
     * Transform the resource collection into an array.
     */
    abstract public function toArray(): array;

    /**
     * @param Collection<int, TModel>|PaginatorInterface $resource
     */
    public static function make(Collection|PaginatorInterface $resource): static
    {
        return new static($resource);
    }
}
