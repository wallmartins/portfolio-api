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

namespace App\Request\Admin;

use Hyperf\Validation\Request\FormRequest;

class UpdateSocialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'social_name' => 'sometimes|required|string|max:255',
            'social_url' => 'sometimes|required|url|max:500',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'social_name.required' => 'The social name is required',
            'social_name.max' => 'The social name cannot exceed 255 characters',
            'social_url.required' => 'The social URL is required',
            'social_url.url' => 'The social URL must be a valid URL',
            'social_url.max' => 'The social URL cannot exceed 500 characters',
        ];
    }
}
