<?php

/**
 * This file is part of the SI project.
 *
 * (c) Students
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
     * @param User $author Author
     * @param int  $page   Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(User $author, int $page): PaginationInterface;

    /**
     * Find one category by ID.
     *
     * @param int $id Category ID
     *
     * @return Category|null Category entity
     */
    public function findOneById(int $id): ?Category;

    /**
     * Find category by ID.
     *
     * @param int $id Category ID
     *
     * @return Category|null Category entity
     */
    public function findById(int $id): ?Category;

    /**
     * Save category.
     *
     * @param Category $category Category entity
     */
    public function save(Category $category): void;

    /**
     * Delete category.
     *
     * @param Category $category Category entity
     */
    public function delete(Category $category): void;
}
