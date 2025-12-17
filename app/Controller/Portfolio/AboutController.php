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

namespace App\Controller\Portfolio;

use App\DTO\AboutDTO;
use App\Services\AboutService;
use Hyperf\HttpServer\Contract\RequestInterface;

class AboutController
{
    public function __construct(
        private readonly AboutService $aboutService,
    ) {
    }

    public function index(RequestInterface $request): AboutDTO
    {
        $locate = $request->query('locale');
        return $this->aboutService->getAbout($locate);
    }
}
