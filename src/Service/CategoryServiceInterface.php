<?php

/**
 * Category service interface.
 */

namespace App\Service;

use App\Entity\Category;
use App\Entity\User;
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
    public function getPaginatedList(int $page, User $author): PaginationInterface;

    public function findOneById(int $id): ?Category;
    public function findById(int $id): ?Category;

    public function save(Category $category): void;

    public function delete(Category $category): void;
}
