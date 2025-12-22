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

class CreateProjectRequest extends BaseProjectRequest
{
    public function rules(): array
    {
        return [
            'name' => 'bail|required|string',
            'slug' => 'bail|required|string|unique:projects,slug',
            'image' => 'sometimes|string',
            'translations' => 'bail|required|array|min:1',
            'translations.locale' => 'bail|required|string|in:pt-BR,en-US',
            'translations.title' => 'bail|required|string',
            'translations.content' => 'bail|required|string',
            'techs.ids' => 'bail|required|array|min:1',
            'techs.ids.*' => 'bail|required|integer|exists:techs,id',
        ];
    }
}
