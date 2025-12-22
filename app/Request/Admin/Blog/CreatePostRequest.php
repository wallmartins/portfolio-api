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

class CreatePostRequest extends BasePostRequest
{
    public function rules(): array
    {
        return [
            'slug' => 'bail|required|string|unique:posts,slug',
            'image' => 'nullable|string',
            'translations' => 'bail|required|array|min:1',
            'translations.*.locale' => 'bail|required|string|in:pt-BR,en-US',
            'translations.*.title' => 'bail|required|string',
            'translations.*.subtitle' => 'nullable|string',
            'translations.*.content' => 'bail|required|string',
            'tech_ids' => 'bail|required|array|min:1',
            'tech_ids.*' => 'bail|required|integer|exists:techs,id',
        ];
    }
}
