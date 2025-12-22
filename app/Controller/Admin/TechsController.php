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

namespace App\Controller\Admin;

use App\Resource\Tech\TechCollection;
use App\Resource\Tech\TechResource;
use App\Services\Tech\TechService;
use App\Traits\RespondsWithResource;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Di\Exception\NotFoundException;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

class TechsController
{
    use RespondsWithResource;

    #[Inject]
    protected ResponseInterface $response;

    public function __construct(private readonly TechService $techService)
    {
    }

    public function index(): PsrResponseInterface
    {
        $tech = $this->techService->getAll();
        return $this->jsonResource(TechCollection::make($tech));
    }

    /**
     * @throws NotFoundException
     */
    public function show(int $id): PsrResponseInterface
    {
        $tech = $this->techService->getById($id);
        return $this->jsonResource(TechResource::make($tech));
    }

    public function store(array $data): PsrResponseInterface
    {
        $tech = $this->techService->create($data);
        return $this->jsonResource(TechResource::make($tech));
    }

    /**
     * @throws NotFoundException
     */
    public function update(int $id, array $data): PsrResponseInterface
    {
        $tech = $this->techService->update($id, $data);
        return $this->jsonResource(TechResource::make($tech));
    }

    /**
     * @throws NotFoundException
     */
    public function delete(int $id): PsrResponseInterface
    {
        $this->techService->delete($id);
        return $this->noContent('Tech entry deleted successfully');
    }
}
