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

class UpdateTechRequest extends BaseTechRequest
{
    public function rules(): array
    {
        return [
            'id' => 'bail|required|integer|exists:tech,id',
            'slug' => 'bail|sometimes|string|unique:tech,slug',
            'name' => 'bail|sometimes|string',
            'category' => 'bail|sometimes|string',
        ];
    }
}
