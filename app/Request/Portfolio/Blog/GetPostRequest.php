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

class GetPostRequest extends FormRequest
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
            'id' => 'required|string|exists:posts,id',
            'locale' => 'required|string|in:pt-BR,en-US',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'id' => 'The id is required.',
            'locale.required' => 'The locale parameter is required',
            'locale.string' => 'Portfolio Locale must be a string.',
            'locale.in' => 'The locale must be pt-BR or en-US',
        ];
    }
}
