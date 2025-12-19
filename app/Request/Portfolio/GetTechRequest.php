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

class GetTechRequest extends FormRequest
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
            'slug' => 'required',
            'name' => 'required',
            'category' => 'required',
            'start_date' => 'required',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'slug.required' => 'The slug parameter is required',
            'name.required' => 'The name parameter is required',
            'category.required' => 'The category parameter is required',
            'start_date.required' => 'The start_date parameter is required',
        ];
    }
}
