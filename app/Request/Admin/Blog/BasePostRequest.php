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

class BasePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'slug.unique' => 'A post with the same slug already exists.',
            'slug.required' => 'The slug field is required.',
            'slug.string' => 'The slug must be a string.',
            'image.string' => 'The image must be a string.',
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
