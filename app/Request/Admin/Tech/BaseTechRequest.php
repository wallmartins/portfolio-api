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

namespace App\Request\Admin\Tech;

use Hyperf\Validation\Request\FormRequest;

class BaseTechRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'id.required' => 'The field id is required.',
            'id.exists' => 'The field id is not exist.',
            'id.integer' => 'The field id is not integer.',

            'slug.required' => 'The field slug is required.',
            'slug.unique' => 'The field slug has already been taken.',
            'slug.string' => 'The field slug must be a string.',

            'name.required' => 'The field name is required.',
            'name.string' => 'The field name must be a string.',

            'start_date.required' => 'The field start date is required.',
            'start_date.date' => 'The field start date must be a date.',

            'category.required' => 'The field category is required.',
            'category.string' => 'The field category must be a string.',
        ];
    }
}
