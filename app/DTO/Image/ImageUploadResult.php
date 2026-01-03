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

namespace App\DTO\Image;

class ImageUploadResult
{
    public function __construct(
        public readonly string $url,
        public readonly string $secureUrl,
        public readonly string $publicId,
        public readonly string $format,
        public readonly int $width,
        public readonly int $height,
        public readonly int $bytes,
        public readonly ?string $thumbnailUrl = null,
        public readonly ?string $optimizedUrl = null,
    ) {
    }
}
