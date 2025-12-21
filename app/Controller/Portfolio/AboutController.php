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

use App\Request\Portfolio\About\GetAboutRequest;
use App\Resource\About\AboutResource;
use App\Services\About\AboutService;
use App\Traits\RespondsWithResource;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Di\Exception\NotFoundException;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class AboutController
{
    use RespondsWithResource;

    #[Inject]
    protected ResponseInterface $response;

    public function __construct(
        private readonly AboutService $aboutService,
    ) {
    }

    /**
     * Get about information by locale.
     * @throws NotFoundException
     */
    public function index(GetAboutRequest $request): PsrResponseInterface
    {
        $validated = $request->validated();
        $about = $this->aboutService->getByLocale($validated['locale']);
        return $this->jsonResource(AboutResource::make($about));
    }
}
