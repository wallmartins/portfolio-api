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

namespace App\Request\Admin\Project;

use Hyperf\Validation\Request\FormRequest;

class BaseProjectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function message(): array
    {
        return [
            'id.required' => 'The id field is required.',
            'id.exists' => 'The id does not exist.',
            'id.integer' => 'The id must be an integer.',

            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',

            'slug.required' => 'The slug field is required.',
            'slug.string' => 'The slug must be a string.',

            'image.string' => 'The image must be a string.',

            'translations.required' => 'The translation field is required.',
            'translations.array' => 'The translation field must be an array.',
            'translations.min' => 'At least one translation is required.',

            'translations.*.locale.required' => 'Translation locale is required.',
            'translations.*.locale.in' => 'Translation locale must be pt-BR or en-US.',

            'translations.*.title.required' => 'Translation title is required.',
            'translations.*.title.string' => 'Translation title must be a string.',

            'translations.*.content.required' => 'Translation content is required.',
            'translations.*.content.string' => 'Translation content must be a string.',

            'techs.required' => 'Techs are required.',
            'techs.array' => 'Techs must be an array.',
            'techs.min' => 'At least one tech is required.',

            'techs.*.required' => 'Tech ID is required.',
            'techs.*.integer' => 'Tech ID must be an integer.',
            'techs.*.exists' => 'Tech does not exist.',
        ];
    }
}
