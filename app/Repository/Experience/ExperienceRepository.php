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

namespace App\Repository\Experience;

use App\Model\Experience\Experience;
use Exception;
use Hyperf\Collection\Collection;

class ExperienceRepository
{
    public function getAll(): Collection
    {
        return Experience::query()
            ->with(['translations', 'techs'])
            ->get();
    }

    public function getById(int $id): ?Experience
    {
        return Experience::query()
            ->with(['translations', 'techs'])
            ->find($id);
    }

    public function create(array $data): Experience
    {
        $experience = Experience::create([
            'company_name' => $data['company_name'],
        ]);

        if (isset($data['translations'])) {
            foreach ($data['translations'] as $translation) {
                $experience->translations()->create($translation);
            }
        }

        if (isset($data['techs'])) {
            $experience->techs()->attach($data['techs']);
        }

        return $experience->load(['translations', 'techs']);
    }

    public function update(Experience $experience, array $data): Experience
    {
        if (isset($data['company_name'])) {
            $experience->update(['company_name' => $data['company_name']]);
        }

        if (isset($data['translations'])) {
            $experience->translations()->delete();

            foreach ($data['translations'] as $translation) {
                $experience->translations()->create($translation);
            }
        }

        if (isset($data['techs'])) {
            $experience->techs()->sync($data['techs']);
        }

        return $experience->load(['translations', 'techs']);
    }

    /**
     * @throws Exception
     */
    public function delete(Experience $experience): bool
    {
        return $experience->delete();
    }
}
