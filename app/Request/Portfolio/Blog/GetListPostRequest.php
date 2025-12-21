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

namespace App\Request\Portfolio\Blog;

use Hyperf\Validation\Request\FormRequest;

class GetListPostRequest extends FormRequest
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
            'locale' => 'required|string|in:pt-BR,en-US',
            'page' => 'required|integer|min:1',
            'per_page' => 'required|integer|min:1',
            'search' => 'nullable|string|in:all,none',
            'order_by' => 'nullable|string|in:created_at,title',
            'order' => 'nullable|string|in:asc,desc',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'page' => 'The page parameter is required',
            'per_page' => 'The per_page parameter is required',
            'locale.required' => 'The locale parameter is required',
            'locale.string' => 'Portfolio Locale must be a string.',
            'locale.in' => 'The locale must be pt-BR or en-US',
        ];
    }
}
