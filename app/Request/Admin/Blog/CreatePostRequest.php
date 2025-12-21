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

use Hyperf\Validation\Request\FormRequest;

class CreatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'slug' => 'required|string|unique:posts,slug',
            'translations' => 'required|array|min:1',
            'translations.*.locale' => 'required|string|in:pt-BR,en-US',
            'translations.*.title' => 'required|string',
            'translations.*.content' => 'required|string',
            'tech_ids' => 'required|array|min:1',
            'tech_ids.*' => 'required|integer|exists:techs,id',
        ];
    }

    public function messages(): array
    {
        return [
            'slug.unique' => 'A post with the same slug already exists.',
            'translations.required' => 'At least one translation field is required.',
            'locale.*.locale.in' => 'Locale must be pt-BR or en-US.',
            'tech_ids.*.exists' => 'One or more technologies do not exist.',
        ];
    }
}
