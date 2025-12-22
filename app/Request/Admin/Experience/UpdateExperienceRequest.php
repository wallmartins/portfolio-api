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

class UpdateExperienceRequest extends BaseExperienceRequest
{
    public function rules(): array
    {
        return [
            'id' => 'bail|required|integer|exists:experiences,id',
            'company_name' => 'sometimes|required|string|max:255',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date',
            'translations' => 'sometimes|required|array|min:1',
            'translation.locale' => 'sometimes|required|string|in:pt-BR,en-US',
            'translation.position' => 'sometimes|required|string|max:255',
            'translation.description' => 'sometimes|required|string',
            'techs.ids' => 'sometimes|required|array|min:1',
            'techs.ids.*' => 'sometimes|required|integer|exists:techs,id',
        ];
    }

    public function messages(): array
    {
        return array_merge(parent::messages(), [
            'id.required' => 'The id field is required.',
            'id.exists' => 'The id must be a existing.',
            'id.integer' => 'The id must be an integer.',
        ]);
    }
}
