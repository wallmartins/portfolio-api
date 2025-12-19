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

use App\Model\Tech;
use Exception;
use Hyperf\Collection\Collection;

class TechRepository
{
    public function getAll(): Collection
    {
        return Tech::all();
    }

    public function findById(int $id): ?Tech
    {
        return Tech::find($id);
    }

    public function create(Tech $tech): Tech
    {
        return Tech::create($tech);
    }

    public function update(Tech $tech, array $data): Tech
    {
        $tech->update($data);
        return $tech->refresh();
    }

    /**
     * @throws Exception
     */
    public function delete(Tech $tech): bool
    {
        return $tech->delete();
    }
}
