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

namespace App\Services\About;

use App\Model\About\About;
use App\Repository\About\AboutRepository;
use App\Services\Image\ImageUploadService;
use Exception;
use Hyperf\Database\Model\Collection;
use Hyperf\Di\Exception\NotFoundException;
use Hyperf\HttpMessage\Upload\UploadedFile;

class AboutService
{
    public function __construct(
        public readonly AboutRepository $aboutRepository,
        protected ImageUploadService $imageUploadService,
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
     * @throws NotFoundException
     */
    public function getById(int $id): About
    {
        $about = $this->aboutRepository->getById($id);

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
        $uploadedImageUrl = null;

        try {
            // Handle image upload if present
            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $result = $this->imageUploadService->upload($data['image'], 'about');
                $uploadedImageUrl = $result->secureUrl;
                $data['image'] = $uploadedImageUrl;
            }

            $about = $this->aboutRepository->create($data);
            return $about;
        } catch (\Exception $e) {
            // Rollback: delete uploaded image if about creation failed
            if ($uploadedImageUrl) {
                $this->imageUploadService->delete($uploadedImageUrl);
            }
            throw $e;
        }
    }

    /**
     * Update an about entry.
     * @throws NotFoundException
     */
    public function update(int $id, array $data): About
    {
        $about = $this->getById($id);
        $oldImageUrl = $about->image;
        $uploadedImageUrl = null;

        try {
            // Handle image upload if present
            if (isset($data['image']) && $data['image'] instanceof UploadedFile) {
                $result = $this->imageUploadService->upload($data['image'], 'about');
                $uploadedImageUrl = $result->secureUrl;
                $data['image'] = $uploadedImageUrl;
            }

            $updatedAbout = $this->aboutRepository->update($about, $data);

            // Delete old image if new one was uploaded successfully
            if ($uploadedImageUrl && $oldImageUrl) {
                $this->imageUploadService->delete($oldImageUrl);
            }

            return $updatedAbout;
        } catch (\Exception $e) {
            // Rollback: delete uploaded image if update failed
            if ($uploadedImageUrl) {
                $this->imageUploadService->delete($uploadedImageUrl);
            }
            throw $e;
        }
    }

    /**
     * Delete an about entry.
     * @throws NotFoundException
     * @throws Exception
     */
    public function delete(int $id): bool
    {
        $about = $this->getById($id);

        // Delete image from Cloudinary if exists
        if ($about && $about->image) {
            $this->imageUploadService->delete($about->image);
        }

        return $this->aboutRepository->delete($about);
    }
}
