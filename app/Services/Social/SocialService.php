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

namespace App\Services\Social;

use App\Model\Social\Social;
use App\Repository\Social\SocialRepository;
use Exception;
use Hyperf\Database\Model\Collection;
use Hyperf\Di\Exception\NotFoundException;

class SocialService
{
    public function __construct(
        protected SocialRepository $socialRepository,
    ) {
    }

    /**
     * Get all social media links.
     */
    public function getAll(): Collection
    {
        return $this->socialRepository->getAll();
    }

    /**
     * Get a social media link by ID.
     * @throws NotFoundException
     */
    public function getById(int $id): Social
    {
        $social = $this->socialRepository->getById($id);

        if (! $social) {
            throw new NotFoundException('Social media link not found');
        }

        return $social;
    }

    /**
     * Create a new social media link.
     */
    public function create(array $data): Social
    {
        return $this->socialRepository->create($data);
    }

    /**
     * Update a social media link.
     * @throws NotFoundException
     */
    public function update(int $id, array $data): Social
    {
        $social = $this->getById($id);
        return $this->socialRepository->update($social, $data);
    }

    /**
     * Delete a social media link.
     * @throws NotFoundException
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        $social = $this->getById($id);
        return $this->socialRepository->delete($social);
    }
}
