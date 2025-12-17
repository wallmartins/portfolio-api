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

namespace App\Model;

use Carbon\Carbon;

/**
 * @property int $id
 * @property int $user_id
 * @property string $token
 * @property Carbon $expires_at
 * @property Carbon $revoked_at
 * @property Carbon $created_at
 */
class RefreshToken extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'refresh_tokens';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'user_id',
        'token',
        'expires_at',
        'revoked_at',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [
        'id' => 'integer',
        'user_id' => 'integer',
        'token' => 'string',
        'expires_at' => 'datetime',
        'revoked_at' => 'datetime',
        'created_at' => 'datetime',
    ];
}
