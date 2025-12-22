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

class UpdateProjectRequest extends BaseProjectRequest
{
    public function rules(): array
    {
        return [
            'id' => 'bail|required|integer|exists:projects,id',
            'name' => 'bail|sometimes|string',
            'slug' => 'bail|sometimes|string|unique:projects,slug',
            'image' => 'bail|sometimes|string',
            'translations' => 'bail|sometimes|array|min:1',
            'translations.*.locale' => 'bail|sometimes|string|in:pt-BR,en-US',
            'translations.*.title' => 'bail|sometimes|string',
            'translations.*.content' => 'bail|sometimes|string',
            'techs.ids' => 'bail|sometimes|array|min:1',
            'techs.ids.*' => 'bail|sometimes|integer|exists:techs,id',
        ];
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'id.required' => 'The id field is required.',
            'id.exists' => 'The id must be a existing.',
            'id.integer' => 'The id must be an integer.',
        ]);
    }
}
