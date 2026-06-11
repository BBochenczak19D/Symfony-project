<?php
/**
 * Operation list filters DTO.
 */

namespace App\DTO;

use App\Entity\Category;
use App\Entity\Tag;

/**
 * Class OperationListFiltersDto.
 *
 * Przechowuje obiekty encji do filtrowania w warstwie repozytorium.
 * Tworzona przez WalletService::prepareFilters() na podstawie OperationListInputFiltersDto.
 */
class OperationListFiltersDTO
{
    /**
     * Constructor.
     *
     * @param Category|null        $category        Category entity
     * @param Tag|null             $tag             Tag entity
     * @param OperationStatus|null $operationStatus Operation status
     */
    public function __construct(
        public readonly ?Category $category,
        public readonly ?Tag $tag,
    ) {
    }
}
