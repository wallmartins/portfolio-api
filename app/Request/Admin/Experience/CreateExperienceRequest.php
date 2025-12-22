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

class CreateExperienceRequest extends BaseExperienceRequest
{
    public function rules(): array
    {
        return [
            'company_name' => 'bail|required|string|max:255',
            'start_date' => 'bail|required|date',
            'end_date' => 'bail|required|date',
            'translations' => 'bail|required|array|min:1',
            'translation.locale' => 'bail|required|string|in:pt-BR,en-US',
            'translation.position' => 'bail|required|string|max:255',
            'translation.description' => 'bail|required|string',
            'techs.ids' => 'bail|required|array|min:1',
            'techs.ids.*' => 'bail|required|integer|exists:techs,id',
        ];
    }
}
