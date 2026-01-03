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
use App\Exception\Image\CloudinaryException;
use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Cloudinary;
use Hyperf\HttpMessage\Upload\UploadedFile;
use Hyperf\Logger\LoggerFactory;
use Psr\Log\LoggerInterface;
use function Hyperf\Config\config;

class CloudinaryService
{
    private Cloudinary $cloudinary;

    private LoggerInterface $logger;

    public function __construct(LoggerFactory $loggerFactory)
    {
        $this->logger = $loggerFactory->get('cloudinary');
        $this->initializeCloudinary();
    }

    /**
     * Upload file to Cloudinary.
     */
    public function upload(UploadedFile $file, string $folder = ''): ImageUploadResult
    {
        $startTime = microtime(true);

        try {
            $config = config('cloudinary');
            $uploadFolder = $config['folder'];

            if ($folder) {
                $uploadFolder .= '/' . $folder;
            }

            $this->logger->info('Uploading image to Cloudinary', [
                'folder' => $uploadFolder,
                'original_name' => $file->getClientFilename(),
                'size' => $file->getSize(),
            ]);

            // Upload to Cloudinary
            $result = $this->cloudinary->uploadApi()->upload(
                $file->getRealPath(),
                [
                    'folder' => $uploadFolder,
                    'resource_type' => 'image',
                    'transformation' => [
                        'quality' => 'auto:good',
                        'fetch_format' => 'auto',
                    ],
                ]
            );

            $duration = round((microtime(true) - $startTime) * 1000, 2);

            $this->logger->info('Image uploaded successfully to Cloudinary', [
                'public_id' => $result['public_id'],
                'url' => $result['secure_url'],
                'duration_ms' => $duration,
                'size_bytes' => $result['bytes'],
            ]);

            // Generate transformation URLs
            $thumbnailUrl = $this->getTransformationUrl($result['public_id'], 'thumbnail');
            $optimizedUrl = $this->getTransformationUrl($result['public_id'], 'optimized');

            return new ImageUploadResult(
                url: $result['url'],
                secureUrl: $result['secure_url'],
                publicId: $result['public_id'],
                format: $result['format'],
                width: $result['width'],
                height: $result['height'],
                bytes: $result['bytes'],
                thumbnailUrl: $thumbnailUrl,
                optimizedUrl: $optimizedUrl,
            );
        } catch (ApiError $e) {
            $this->logger->error('Cloudinary API error during upload', [
                'error' => $e->getMessage(),
                'file' => $file->getClientFilename(),
            ]);

            throw new CloudinaryException(
                'Cloudinary upload failed: ' . $e->getMessage(),
                $e->getCode(),
                $e
            );
        } catch (\Exception $e) {
            $this->logger->error('Unexpected error during Cloudinary upload', [
                'error' => $e->getMessage(),
                'file' => $file->getClientFilename(),
            ]);

            throw new CloudinaryException(
                'Failed to upload image: ' . $e->getMessage(),
                500,
                $e
            );
        }
    }

    /**
     * Delete image from Cloudinary using public ID.
     */
    public function deleteByPublicId(string $publicId): bool
    {
        try {
            $this->logger->info('Deleting image from Cloudinary', [
                'public_id' => $publicId,
            ]);

            $result = $this->cloudinary->uploadApi()->destroy($publicId);

            if ($result['result'] === 'ok') {
                $this->logger->info('Image deleted successfully from Cloudinary', [
                    'public_id' => $publicId,
                ]);
                return true;
            }

            $this->logger->warning('Image deletion returned non-ok status', [
                'public_id' => $publicId,
                'result' => $result['result'],
            ]);

            return false;
        } catch (ApiError $e) {
            $this->logger->error('Cloudinary API error during delete', [
                'error' => $e->getMessage(),
                'public_id' => $publicId,
            ]);

            // Don't throw exception on delete failure, just log and return false
            return false;
        } catch (\Exception $e) {
            $this->logger->error('Unexpected error during Cloudinary delete', [
                'error' => $e->getMessage(),
                'public_id' => $publicId,
            ]);

            return false;
        }
    }

    /**
     * Extract public ID from Cloudinary URL.
     */
    public function extractPublicId(string $url): ?string
    {
        // Cloudinary URL format: https://res.cloudinary.com/{cloud_name}/image/upload/{transformations}/{public_id}.{format}
        // We need to extract the public_id

        if (! str_contains($url, 'cloudinary.com')) {
            return null;
        }

        $pattern = '/\/v?\d+\/(.+)\.\w+$/';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        // Try alternative pattern without version
        $pattern = '/\/upload\/(?:v\d+\/)?(.+)\.\w+$/';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Get transformation URL for a public ID.
     */
    public function getTransformationUrl(string $publicId, string $transformationType): string
    {
        $config = config('cloudinary');
        $transformation = $config['transformations'][$transformationType] ?? [];

        if (empty($transformation)) {
            // Return original URL if transformation not found
            return (string) $this->cloudinary->image($publicId)->toUrl();
        }

        // Build transformation string
        $transformParts = [];

        if (isset($transformation['width'])) {
            $transformParts[] = 'w_' . $transformation['width'];
        }

        if (isset($transformation['height'])) {
            $transformParts[] = 'h_' . $transformation['height'];
        }

        if (isset($transformation['crop'])) {
            $transformParts[] = 'c_' . $transformation['crop'];
        }

        if (isset($transformation['quality'])) {
            $transformParts[] = 'q_' . str_replace(':', '_', $transformation['quality']);
        }

        if (isset($transformation['fetch_format'])) {
            $transformParts[] = 'f_' . $transformation['fetch_format'];
        }

        $transformString = implode(',', $transformParts);

        // Generate URL with transformation
        $baseUrl = (string) $this->cloudinary->image($publicId)->toUrl();

        // Insert transformation into URL (after /upload/)
        return str_replace('/upload/', '/upload/' . $transformString . '/', $baseUrl);
    }

    /**
     * Initialize Cloudinary SDK with configuration.
     */
    private function initializeCloudinary(): void
    {
        $config = config('cloudinary');

        if (empty($config['cloud_name']) || empty($config['api_key']) || empty($config['api_secret'])) {
            $this->logger->warning('Cloudinary credentials not configured properly');
            throw new CloudinaryException('Cloudinary credentials not configured');
        }

        $this->cloudinary = new Cloudinary([
            'cloud' => [
                'cloud_name' => $config['cloud_name'],
                'api_key' => $config['api_key'],
                'api_secret' => $config['api_secret'],
            ],
            'url' => [
                'secure' => $config['secure'] ?? true,
            ],
        ]);

        $this->logger->debug('Cloudinary SDK initialized', [
            'cloud_name' => $config['cloud_name'],
        ]);
    }
}
