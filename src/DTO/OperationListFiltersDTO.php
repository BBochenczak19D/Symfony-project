<?php

/**
 * This file is part of the SI project.
 *
 * (c) Students
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DTO;

use App\Entity\Category;
use App\Entity\Tag;

/**
 * Operation list filters DTO.
 */
class OperationListFiltersDTO
{
    /**
     * Constructor.
     *
     * @param Category|null           $category Category filter
     * @param Tag|null                $tag      Tag filter
     * @param \DateTimeImmutable|null $dateFrom Date from filter
     * @param \DateTimeImmutable|null $dateTo   Date to filter
     */
    public function __construct(public readonly ?Category $category, public readonly ?Tag $tag, public readonly ?\DateTimeImmutable $dateFrom = null, public readonly ?\DateTimeImmutable $dateTo = null)
    {
    }
}
