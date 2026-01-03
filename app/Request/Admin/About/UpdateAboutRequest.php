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

namespace App\Request\Admin\About;

use Hyperf\HttpMessage\Upload\UploadedFile;
use Hyperf\Validation\Request\FormRequest;
use function Hyperf\Config\config;

class UpdateAboutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
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

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'image' => 'nullable', // Validated in authorize() to accept both file and string
            'locale' => 'sometimes|required|string|in:pt-BR,en-US',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The title is required',
            'title.max' => 'The title cannot exceed 255 characters',
            'description.required' => 'The description is required',
            'image' => 'The image must be a valid URL or file upload',
            'locale.required' => 'The locale is required',
            'locale.in' => 'The locale must be pt-BR or en-US',
        ];
    }
}
