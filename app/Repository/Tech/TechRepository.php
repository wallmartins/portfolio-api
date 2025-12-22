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

namespace App\Repository\Tech;

use App\Model\Tech\Tech;
use Exception;
use Hyperf\Database\Model\Collection;

class TechRepository
{
    public function getAll(): Collection
    {
        return Tech::all();
    }

    public function getById(int $id): ?Tech
    {
        return Tech::find($id);
    }

    public function create(array $data): Tech
    {
        return Tech::create($data);
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
