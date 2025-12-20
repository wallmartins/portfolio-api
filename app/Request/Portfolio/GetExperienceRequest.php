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

class GetExperienceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'locale' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'locale.required' => 'The locale parameter is required',
            'locale.string' => 'Portfolio Locale must be a string.',
            'locale.in' => 'The locale must be pt-BR or en-US',
        ];
    }
}
