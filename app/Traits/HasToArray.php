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

use Carbon\Carbon;
use DateTime;
use ReflectionClass;
use ReflectionProperty;

trait HasToArray
{
    /**
     * Convert the DTO instance to an array.
     */
    public function toArray(): array
    {
        $reflection = new ReflectionClass($this);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        $result = [];

        foreach ($properties as $property) {
            $name = $property->getName();
            $value = $property->getValue($this);

            $result[$name] = $this->transformValue($value);
        }

        return $result;
    }

    /**
     * Transform a value for array conversion.
     */
    protected function transformValue(mixed $value): mixed
    {
        // Handle null values
        if ($value === null) {
            return null;
        }

        // Handle nested DTOs
        if (is_object($value) && method_exists($value, 'toArray')) {
            return $value->toArray();
        }

        // Handle DateTime instances
        if ($value instanceof DateTime || $value instanceof Carbon) {
            return $value->format('Y-m-d H:i:s');
        }

        // Handle arrays (in case of collections of DTOs)
        if (is_array($value)) {
            return array_map(fn ($item) => $this->transformValue($item), $value);
        }

        return $value;
    }
}
