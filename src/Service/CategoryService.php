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
use App\Repository\CategoryRepository;
use App\Repository\OperationRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CategoryService.
 */
class CategoryService implements CategoryServiceInterface
{
    /**
     * Items per page.
     *
     * @var int
     */
    private const int PAGINATOR_ITEMS_PER_PAGE = 5;

    /**
     * Constructor.
     *
     * @param PaginatorInterface  $paginator           Paginator
     * @param CategoryRepository  $categoryRepository  Category repository
     * @param OperationRepository $operationRepository Operation repository
     */
    public function __construct(private readonly PaginatorInterface $paginator, private readonly CategoryRepository $categoryRepository, private readonly OperationRepository $operationRepository)
    {
    }

    /**
     * Get paginated list.
     *
     * @param User $author Author
     * @param int  $page   Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(User $author, int $page = 1): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->categoryRepository->queryAll($author),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE,
            [
                'sortFieldAllowList' => ['category.id', 'category.name'],
                'defaultSortFieldName' => 'category.name',
                'defaultSortDirection' => 'asc',
            ]
        );
    }

    /**
     * Find category by ID (one).
     *
     * @param int $id Category ID
     *
     * @return Category|null Category entity
     */
    public function findOneById(int $id): ?Category
    {
        return $this->categoryRepository->findOneById($id);
    }

    /**
     * Find category by ID.
     *
     * @param int $id Category ID
     *
     * @return Category|null Category entity
     */
    public function findById(int $id): ?Category
    {
        return $this->categoryRepository->find($id);
    }

    /**
     * Save category.
     *
     * @param Category $category Category entity
     */
    public function save(Category $category): void
    {
        $this->categoryRepository->save($category);
    }

    /**
     * Delete category.
     *
     * @param Category $category Category entity
     */
    public function delete(Category $category): void
    {
        $this->operationRepository->nullifyCategory($category);
        $this->categoryRepository->delete($category);
    }
}
