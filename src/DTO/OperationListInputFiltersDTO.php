<?php
/**
 * Operation list input filters DTO.
 */

namespace App\DTO;

/**
 * Class OperationListInputFiltersDto.
 *
 **/
class OperationListInputFiltersDTO
{
    /**
     * Constructor.
     *
     * @param int|null $categoryId Category identifier
     * @param int|null $tagId      Tag identifier
     * @param int      $statusId   Status identifier
     */
    public function __construct(
        public readonly ?int $categoryId = null,
        public readonly ?int $tagId = null,
        public readonly int $statusId = 1,
    ) {
    }
}
