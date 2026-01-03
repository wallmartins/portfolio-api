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

namespace App\Exception\Image;

use App\Exception\BusinessException;
use Throwable;

class ImageValidationException extends BusinessException
{
    public function __construct(?string $message = 'Image validation failed', int $code = 422, ?Throwable $previous = null)
    {
        parent::__construct($code, $message, $previous);
    }
}
