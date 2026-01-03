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

class ImageUploadException extends BusinessException
{
    public function __construct(?string $message = 'Failed to upload image', int $code = 500, ?Throwable $previous = null)
    {
        parent::__construct($code, $message, $previous);
    }
}
