<?php

/**
 * Category service interface.
 */

namespace App\Service;

use App\Entity\Category;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface CategoryServiceInterface.
 */
interface CategoryServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    /**
     * @param int $id
     * @return Category|null
     */
    public function findById(int $id): ?Category;

    /**
     * @param Category $category
     * @return void
     */
    public function save(Category $category): void;

    /**
     * @param Category $category
     * @return void
     */
    public function delete(Category $category): void;

}
