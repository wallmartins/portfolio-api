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

use Hyperf\Validation\Request\FormRequest;

class CreateAboutRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|string|max:500',
            'locale' => 'required|string|in:pt-BR,en-US',
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
            'image.max' => 'The image path cannot exceed 500 characters',
            'locale.required' => 'The locale is required',
            'locale.in' => 'The locale must be pt-BR or en-US',
        ];
    }
}
