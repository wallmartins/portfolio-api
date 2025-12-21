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

namespace App\Repository\Social;

use App\Model\Social\Social;
use Exception;
use Hyperf\Database\Model\Collection;

class SocialRepository
{
    /**
     * Get all social media links.
     */
    public function getAll(): Collection
    {
        return Social::all();
    }

    /**
     * Find a social media link by ID.
     */
    public function findById(int $id): ?Social
    {
        return Social::find($id);
    }

    /**
     * Create a new social media link.
     */
    public function create(array $data): Social
    {
        return Social::create($data);
    }

    /**
     * Update a social media link.
     */
    public function update(Social $social, array $data): Social
    {
        $social->update($data);
        return $social->refresh();
    }

    /**
     * Delete a social media link.
     * @throws Exception
     */
    public function delete(Social $social): bool
    {
        return $social->delete();
    }
}
