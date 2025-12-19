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

namespace App\Repository;

use App\Model\About;
use Exception;
use Hyperf\Database\Model\Collection;

class AboutRepository
{
    /**
     * Get about by locale.
     */
    public function getByLocale(string $locale): ?About
    {
        return About::query()
            ->where('locale', $locale)
            ->first();
    }

    /**
     * Get all about entries.
     */
    public function getAll(): Collection
    {
        return About::all();
    }

    /**
     * Find an about entry by ID.
     */
    public function findById(int $id): ?About
    {
        return About::find($id);
    }

    /**
     * Create a new about entry.
     */
    public function create(array $data): About
    {
        return About::create($data);
    }

    /**
     * Update an about entry.
     */
    public function update(About $about, array $data): About
    {
        $about->update($data);
        return $about->refresh();
    }

    /**
     * Delete an about entry.
     * @throws Exception
     */
    public function delete(About $about): bool
    {
        return $about->delete();
    }
}
