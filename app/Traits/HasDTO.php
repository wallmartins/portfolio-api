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

namespace App\Traits;

trait HasDTO
{
    /**
     * Convert the model instance to a DTO.
     */
    public function toDTO(): object
    {
        $dtoClass = $this->getDTOClass();
        return new $dtoClass($this->toArray());
    }

    /**
     * Get the DTO class name for this model.
     *
     * @return class-string
     */
    abstract protected function getDTOClass(): string;
}
