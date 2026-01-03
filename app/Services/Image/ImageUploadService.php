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

namespace App\Services\Image;

use App\DTO\Image\ImageUploadResult;
use App\Exception\Image\ImageUploadException;
use App\Exception\Image\ImageValidationException;
use Hyperf\HttpMessage\Upload\UploadedFile;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;
use function Hyperf\Config\config;

class ImageUploadService
{
    private LoggerInterface $logger;

    public function __construct(
        private CloudinaryService $cloudinaryService,
        LoggerFactory $loggerFactory
    ) {
        $this->logger = $loggerFactory->get('image-upload');
    }

    /**
     * Upload image - accepts both UploadedFile and string URL (for backward compatibility).
     *
     * @param UploadedFile|string $image
     * @return ImageUploadResult|string Returns ImageUploadResult for file uploads, string for URL strings
     */
    public function upload(UploadedFile|string $image, string $context = ''): ImageUploadResult|string
    {
        // Handle string URL (backward compatibility)
        if (is_string($image)) {
            $this->logger->debug('Image provided as URL string', [
                'url' => $image,
                'context' => $context,
            ]);
            return $image;
        }

        // Validate uploaded file
        $this->validateFile($image);

        // Upload to Cloudinary
        try {
            $result = $this->cloudinaryService->upload($image, $context);

            $this->logger->info('Image upload completed successfully', [
                'public_id' => $result->publicId,
                'context' => $context,
                'size' => $result->bytes,
            ]);

            return $result;
        } catch (\Exception $e) {
            $this->logger->error('Image upload failed', [
                'error' => $e->getMessage(),
                'context' => $context,
                'file' => $image->getClientFilename(),
            ]);

            throw new ImageUploadException(
                'Failed to upload image: ' . $e->getMessage(),
                500,
                $e
            );
        }
    }

    /**
     * Delete image from Cloudinary.
     * Extracts public ID from URL and deletes the image.
     */
    public function delete(string $imageUrl): bool
    {
        if (empty($imageUrl)) {
            return false;
        }

        // Check if it's a Cloudinary URL
        if (! str_contains($imageUrl, 'cloudinary.com')) {
            $this->logger->debug('Not a Cloudinary URL, skipping deletion', [
                'url' => $imageUrl,
            ]);
            return false;
        }

        $publicId = $this->cloudinaryService->extractPublicId($imageUrl);

        if (! $publicId) {
            $this->logger->warning('Could not extract public ID from Cloudinary URL', [
                'url' => $imageUrl,
            ]);
            return false;
        }

        return $this->cloudinaryService->deleteByPublicId($publicId);
    }

    /**
     * Validate uploaded file.
     *
     * @throws ImageValidationException
     */
    public function validateFile(UploadedFile $file): void
    {
        $config = config('cloudinary');

        // Check if file is valid
        if (! $file->isValid()) {
            throw new ImageValidationException('Invalid file upload');
        }

        // Validate file size
        $maxSize = $config['max_file_size'];
        if ($file->getSize() > $maxSize) {
            $maxSizeMB = round($maxSize / (1024 * 1024), 2);
            throw new ImageValidationException(
                sprintf('File size exceeds maximum allowed (%s MB)', $maxSizeMB)
            );
        }

        // Validate file extension
        $extension = strtolower($file->getExtension());
        $allowedFormats = $config['allowed_formats'];

        if (! in_array($extension, $allowedFormats, true)) {
            throw new ImageValidationException(
                sprintf(
                    'File format not supported. Allowed formats: %s',
                    implode(', ', $allowedFormats)
                )
            );
        }

        // Validate MIME type (server-side check for security)
        $mimeType = $file->getMimeType();
        $allowedMimeTypes = [
            'image/jpeg',
            'image/jpg',
            'image/png',
            'image/webp',
            'image/gif',
        ];

        if (! in_array($mimeType, $allowedMimeTypes, true)) {
            throw new ImageValidationException(
                sprintf('Invalid MIME type: %s. Expected an image file.', $mimeType)
            );
        }

        $this->logger->debug('File validation passed', [
            'filename' => $file->getClientFilename(),
            'size' => $file->getSize(),
            'extension' => $extension,
            'mime_type' => $mimeType,
        ]);
    }
}
