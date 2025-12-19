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

use App\Model\Tech;
use App\Repository\TechRepository;
use Exception;
use Hyperf\Collection\Collection;
use Hyperf\Di\Exception\NotFoundException;

class TechService
{
    public function __construct(
        protected TechRepository $techRepository,
    ) {
    }

    public function getAll(): Collection
    {
        return $this->techRepository->getAll();
    }

    public function getById(int $id): Tech
    {
        $tech = $this->techRepository->findById($id);

        if (! $tech) {
            throw new NotFoundException('Tech not found');
        }

        return $tech;
    }

    public function create(array $data): Tech
    {
        return $this->techRepository->create($data);
    }

    /**
     * @throws NotFoundException
     */
    public function update(int $id, array $data): Tech
    {
        $tech = $this->getById($id);
        return $this->techRepository->update($tech, $data);
    }

    /**
     * @throws NotFoundException
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        $tech = $this->getById($id);
        return $this->techRepository->delete($tech);
    }
}
