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

/**
 * Operation list input filters DTO.
 */
class OperationListInputFiltersDTO
{
    /**
     * Constructor.
     *
     * @param int|null                $categoryId Category id filter
     * @param int|null                $tagId      Tag id filter
     * @param int                     $statusId   Status id filter
     * @param \DateTimeImmutable|null $dateFrom   Date from filter
     * @param \DateTimeImmutable|null $dateTo     Date to filter
     */
    public function __construct(public readonly ?int $categoryId = null, public readonly ?int $tagId = null, public readonly int $statusId = 1, public readonly ?\DateTimeImmutable $dateFrom = null, public readonly ?\DateTimeImmutable $dateTo = null)
    {
    }
}
