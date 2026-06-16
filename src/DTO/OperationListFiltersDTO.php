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
     * @param Category|null $category Category entity
     * @param Tag|null      $tag      Tag entity
     */
    public function __construct(
        public readonly ?Category $category,
        public readonly ?Tag $tag,
    ) {
    }
}
