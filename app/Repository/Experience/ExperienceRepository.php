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
use Hyperf\Database\Model\Collection;

class ExperienceRepository
{
    public function getAll(string $locale): Collection
    {
        return Experience::query()
            ->with(['translations' => function ($query) use ($locale) {
                $query->where('locale', $locale);
            }])
            ->with(['techs'])
            ->get();
    }

    public function getById(int $id, string $locale): ?Experience
    {
        return Experience::query()
            ->with(['translations' => function ($query) use ($locale, $id) {
                $query->where('locale', $locale)->where('experience_id', $id);
            }])
            ->with(['techs'])
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
        $directFields = array_intersect_key($data, array_flip([
            'company_name', 'start_date', 'end_date',
        ]));

        if (! empty($directFields)) {
            $experience->update($directFields);
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
