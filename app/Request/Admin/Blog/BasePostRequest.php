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

namespace App\Request\Admin\Blog;

use Hyperf\HttpMessage\Upload\UploadedFile;
use Hyperf\Validation\Request\FormRequest;
use function Hyperf\Config\config;

class BasePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->validateImageField();
    }

    /**
     * Validate image field - accepts both UploadedFile and string URL.
     */
    protected function validateImageField(): bool
    {
        $image = $this->input('image');

        if ($image === null) {
            return true;
        }

        // If string URL, validate URL format
        if (is_string($image)) {
            return filter_var($image, FILTER_VALIDATE_URL) !== false;
        }

        // If uploaded file, validate file
        if ($image instanceof UploadedFile) {
            return $this->validateUploadedImage($image);
        }

        // Invalid type
        return false;
    }

    /**
     * Validate uploaded image file.
     */
    protected function validateUploadedImage(UploadedFile $file): bool
    {
        $config = config('cloudinary');

        // Validate size
        if ($file->getSize() > $config['max_file_size']) {
            return false;
        }

        // Validate extension
        $extension = strtolower($file->getExtension());
        if (! in_array($extension, $config['allowed_formats'], true)) {
            return false;
        }

        return true;
    }

    public function messages(): array
    {
        return [
            'slug.unique' => 'A post with the same slug already exists.',
            'slug.required' => 'The slug field is required.',
            'slug.string' => 'The slug must be a string.',
            'image.string' => 'The image must be a valid URL or file upload.',
            'translations.required' => 'At least one translation field is required.',
            'translations.*.locale.in' => 'Locale must be pt-BR or en-US.',
            'translations.*.title.required' => 'The title field is required.',
            'translations.*.title.string' => 'The title must be a string.',
            'translations.*.subtitle.string' => 'The subtitle must be a string.',
            'translations.*.content.required' => 'The content field is required.',
            'translations.*.content.string' => 'The content must be a string.',
            'tech_ids.array' => 'Techs must be a array.',
            'tech_ids.*.exists' => 'One or more technologies do not exist.',
        ];
    }
}
