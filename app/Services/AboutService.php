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

use App\Model\About;
use App\Repository\AboutRepository;
use Hyperf\Database\Model\Collection;
use Hyperf\Di\Exception\NotFoundException;

class AboutService
{
    public function __construct(
        public readonly AboutRepository $aboutRepository
    ) {
    }

    /**
     * Get about by locale.
     * @throws NotFoundException
     */
    public function getByLocale(string $locale): About
    {
        $about = $this->aboutRepository->getByLocale($locale);

        if (! $about) {
            throw new NotFoundException("About information not found for locale: {$locale}");
        }

        return $about;
    }

    /**
     * Get all about entries.
     */
    public function getAll(): Collection
    {
        return $this->aboutRepository->getAll();
    }

    /**
     * Get an about entry by ID.
     */
    public function getById(int $id): About
    {
        $about = $this->aboutRepository->findById($id);

        if (! $about) {
            throw new NotFoundException('About information not found');
        }

        return $about;
    }

    /**
     * Create a new about entry.
     */
    public function create(array $data): About
    {
        return $this->aboutRepository->create($data);
    }

    /**
     * Update an about entry.
     * @throws NotFoundException
     */
    public function update(int $id, array $data): About
    {
        $about = $this->getById($id);
        return $this->aboutRepository->update($about, $data);
    }

    /**
     * Delete an about entry.
     * @throws NotFoundException
     */
    public function delete(int $id): bool
    {
        $about = $this->getById($id);
        return $this->aboutRepository->delete($about);
    }
}
