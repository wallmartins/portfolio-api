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

class CloudinaryException extends BusinessException
{
    public function __construct(?string $message = 'Cloudinary service error', int $code = 503, ?Throwable $previous = null)
    {
        parent::__construct($code, $message, $previous);
    }
}
