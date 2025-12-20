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

use App\Resource\TechCollection;
use App\Services\TechService;
use App\Traits\RespondsWithResource;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class TechsController
{
    use RespondsWithResource;

    #[Inject]
    protected ResponseInterface $response;

    public function __construct(
        private readonly TechService $techService
    ) {
    }

    public function index(): PsrResponseInterface
    {
        $tech = $this->techService->getAll();
        return $this->jsonResource(TechCollection::make($tech));
    }
}
