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

namespace App\Services\Project;

use App\Model\Project\Project;
use App\Repository\Project\ProjectRepository;
use App\Services\Image\ImageUploadService;
use Exception;
use Hyperf\Contract\PaginatorInterface;
use Hyperf\HttpMessage\Exception\NotFoundHttpException;
use Hyperf\HttpMessage\Upload\UploadedFile;

class ProjectService
{
    public function __construct(
        protected ProjectRepository $projectRepository,
        protected ImageUploadService $imageUploadService,
    ) {
    }

    public function paginate(array $filters, string $locale, int $perPage = 10): PaginatorInterface
    {
        return $this->projectRepository->paginate($filters, $locale, $perPage);
    }

    public function getById(int $id, string $locale): ?Project
    {
        $project = $this->projectRepository->getById($id, $locale);

        if (! $project) {
            throw new NotFoundHttpException('Project not found');
        }

        return $project;
    }

    public function create(array $data): Project
    {
        $uploadedImageUrl = null;

        try {
            // Handle image upload if present
            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $result = $this->imageUploadService->upload($data['image'], 'projects');
                $uploadedImageUrl = $result->secureUrl;
                $data['image'] = $uploadedImageUrl;
            }

            $project = $this->projectRepository->create($data);
            return $project;
        } catch (\Exception $e) {
            // Rollback: delete uploaded image if project creation failed
            if ($uploadedImageUrl) {
                $this->imageUploadService->delete($uploadedImageUrl);
            }
            throw $e;
        }
    }

    public function update(int $id, string $locale, array $data): Project
    {
        $project = $this->projectRepository->getById($id, $locale);
        $oldImageUrl = $project->image;
        $uploadedImageUrl = null;

        try {
            // Handle image upload if present
            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $result = $this->imageUploadService->upload($data['image'], 'projects');
                $uploadedImageUrl = $result->secureUrl;
                $data['image'] = $uploadedImageUrl;
            }

            $updatedProject = $this->projectRepository->update($project, $data);

            // Delete old image if new one was uploaded successfully
            if ($uploadedImageUrl && $oldImageUrl) {
                $this->imageUploadService->delete($oldImageUrl);
            }

            return $updatedProject;
        } catch (\Exception $e) {
            // Rollback: delete uploaded image if update failed
            if ($uploadedImageUrl) {
                $this->imageUploadService->delete($uploadedImageUrl);
            }
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        $project = Project::find($id);

        // Delete image from Cloudinary if exists
        if ($project && $project->image) {
            $this->imageUploadService->delete($project->image);
        }

        return $this->projectRepository->delete($project);
    }
}
