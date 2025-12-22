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

class CreateTechRequest extends BaseTechRequest
{
    public function rules(): array
    {
        return [
            'slug' => 'bail|required|string|unique:tech,slug',
            'name' => 'bail|required|string',
            'start_date' => 'bail|required|date',
            'category' => 'bail|required|string',
        ];
    }
}
