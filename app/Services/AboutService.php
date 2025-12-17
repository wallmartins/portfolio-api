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

namespace App\Services;

use App\DTO\AboutDTO;
use App\Repository\AboutRepository;

class AboutService
{
    public function __construct(
        public readonly AboutRepository $aboutRepository
    ) {
    }

    public function getAbout(string $locale): AboutDTO
    {
        $about = $this->aboutRepository->getAbout($locale);
        return $about->toDTO();
    }
}
