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
 * Operation list input filters DTO.
 */

namespace App\DTO;

/**
 *
 */
class OperationListInputFiltersDTO
{
    /**
     * @param int|null                $categoryId
     * @param int|null                $tagId
     * @param int                     $statusId
     * @param \DateTimeImmutable|null $dateFrom
     * @param \DateTimeImmutable|null $dateTo
     */
    public function __construct(public readonly ?int $categoryId = null, public readonly ?int $tagId = null, public readonly int $statusId = 1, public readonly ?\DateTimeImmutable $dateFrom = null, public readonly ?\DateTimeImmutable $dateTo = null)
    {
    }
}
