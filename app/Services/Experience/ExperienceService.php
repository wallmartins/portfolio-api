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

namespace App\Services\Experience;

use App\Model\Experience\Experience;
use App\Repository\Experience\ExperienceRepository;
use Exception;
use Hyperf\Database\Model\Collection;

class ExperienceService
{
    public function __construct(
        protected ExperienceRepository $experienceRepository
    ) {
    }

    public function getAll(string $locale): Collection
    {
        return Experience::query()
            ->with(['translations' => function ($query) use ($locale) {
                $query->where('locale', $locale);
            }])
            ->with(['techs'])
            ->get();
    }

    public function getById(int $id, string $locale = 'pt-BR'): Experience
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
        return $this->experienceRepository->create($data);
    }

    public function update(int $id, array $data): Experience
    {
        $experience = $this->getById($id);
        return $this->experienceRepository->update($experience, $data);
    }

    /**
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        $experience = $this->getById($id);
        return $this->experienceRepository->delete($experience);
    }
}
