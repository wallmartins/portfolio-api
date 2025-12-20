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

namespace App\Interface;

use App\Model\Project;
use Hyperf\Contract\PaginatorInterface;

interface ProjectRepositoryInterface
{
    public function paginate(array $filters, string $locale, int $perPage = 10): PaginatorInterface;

    public function getById(int $id, string $locale): ?Project;
}
