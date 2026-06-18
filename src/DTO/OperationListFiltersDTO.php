<?php

/**
 * This file is part of the SI project.
 *
 * (c) Students
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Operation list filters DTO.
 */

namespace App\DTO;

use App\Entity\Category;
use App\Entity\Tag;

/**
 *
 */
class OperationListFiltersDTO
{
    /**
     * @param Category|null           $category
     * @param Tag|null                $tag
     * @param \DateTimeImmutable|null $dateFrom
     * @param \DateTimeImmutable|null $dateTo
     */
    public function __construct(public readonly ?Category $category, public readonly ?Tag $tag, public readonly ?\DateTimeImmutable $dateFrom = null, public readonly ?\DateTimeImmutable $dateTo = null)
    {
    }
}
