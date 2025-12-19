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

namespace App\Request\Portfolio;

use Hyperf\Validation\Request\FormRequest;

class UpdatePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $postSlug = $this->route('slug');

        return [
            'slug' => "required|string|max:255|unique:posts,{$postSlug}",
            'translations' => 'sometimes|required|array|min:1',
            'translations.*.locale' => 'required|string|in:pt-BR,en-US',
            'translations.*.title' => 'sometimes|required|string|max:255',
            'translations.*.subtitle' => 'sometimes|required|string|max:255',
            'translations.*.content' => 'sometimes|required|string',
            'tech_ids' => 'sometimes|required|array|min:1',
            'tech_ids.*' => 'sometimes|required|integer|exists:techs,id',
        ];
    }

    public function messages(): array
    {
        return [
            'slug.required' => 'The slug field is required.',
            'locale.*.locale.in' => 'Locale must be pt-BR or en-US.',
            'tech_ids.*.exists' => 'One or more technologies do not exist.',
        ];
    }
}
