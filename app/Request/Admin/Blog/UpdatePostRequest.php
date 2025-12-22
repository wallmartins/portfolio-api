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

class UpdatePostRequest extends BasePostRequest
{
    public function rules(): array
    {
        $postSlug = $this->route('slug');

        return [
            'id' => 'bail|required|integer',
            'slug' => "required|string|max:255|unique:posts,{$postSlug}",
            'image' => 'nullable|string',
            'translations' => 'sometimes|required|array|min:1',
            'translations.*.locale' => 'required|string|in:pt-BR,en-US',
            'translations.*.title' => 'sometimes|required|string|max:255',
            'translations.*.subtitle' => 'nullable|string|max:255',
            'translations.*.content' => 'sometimes|required|string',
            'tech_ids' => 'sometimes|required|array|min:1',
            'tech_ids.*' => 'sometimes|required|integer|exists:techs,id',
        ];
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'id.required' => 'The post id field is required.',
            'id.exists' => 'The post id does not exist.',
            'id.integer' => 'The post id must be an integer.',
        ]);
    }
}
