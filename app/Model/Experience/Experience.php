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

namespace App\Model\Experience;

use App\Model\Model;
use App\Model\Tech\Tech;
use Carbon\Carbon;
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Relations\BelongsToMany;
use Hyperf\Database\Model\Relations\HasMany;

/**
 * @property int $id
 * @property string $company_name
 * @property Carbon $start_date
 * @property Carbon $end_date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Collection<int, ExperienceTranslation> $translations
 * @property Collection<int, Tech> $techs
 */
class Experience extends Model
{
    /**
     * The table associated with the model.
     */
    protected ?string $table = 'experiences';

    /**
     * The attributes that are mass assignable.
     */
    protected array $fillable = [
        'company_name',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that should be cast to native types.
     */
    protected array $casts = [
        'id' => 'integer',
        'company_name' => 'string',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(ExperienceTranslation::class);
    }

    public function techs(): BelongsToMany
    {
        return $this->belongsToMany(
            Tech::class,
            'experience_tech',
            'experience_id',
            'tech_id'
        );
    }
}
