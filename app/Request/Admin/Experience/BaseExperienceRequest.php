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

namespace App\Request\Admin\Experience;

use Hyperf\Validation\Request\FormRequest;

class BaseExperienceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'id.required' => 'The experience ID is required.',
            'id.exists' => 'The experience does not exist.',
            'id.integer' => 'The experience ID must be an integer.',

            'company_name.required' => 'Company name is required.',
            'company_name.string' => 'Company name must be a string.',
            'company_name.max' => 'Company name cannot exceed 255 characters.',

            'start_date.required' => 'Start date is required.',
            'start_date.date' => 'Start date must be a valid date.',

            'end_date.required' => 'End date is required.',
            'end_date.date' => 'End date must be a valid date.',

            'translations.required' => 'Translations are required.',
            'translations.array' => 'Translations must be an array.',
            'translations.min' => 'At least one translation is required.',

            'translations.*.locale.required' => 'Translation locale is required.',
            'translations.*.locale.in' => 'Translation locale must be pt-BR or en-US.',

            'translations.*.position.required' => 'Position is required.',
            'translations.*.position.string' => 'Position must be a string.',
            'translations.*.position.max' => 'Position cannot exceed 255 characters.',

            'translations.*.description.required' => 'Description is required.',
            'translations.*.description.string' => 'Description must be a string.',

            'techs.required' => 'Techs are required.',
            'techs.array' => 'Techs must be an array.',
            'techs.min' => 'At least one tech is required.',

            'techs.*.required' => 'Tech ID is required.',
            'techs.*.integer' => 'Tech ID must be an integer.',
            'techs.*.exists' => 'Tech does not exist.',
        ];
    }
}
