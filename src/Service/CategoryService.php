<?php

namespace App\Service;

use App\Entity\Category;
use App\Entity\User;
use App\Repository\CategoryRepository;
use App\Repository\OperationRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class CategoryService implements CategoryServiceInterface
{
    private const int PAGINATOR_ITEMS_PER_PAGE = 5;

    public function __construct(
        private readonly PaginatorInterface $paginator,
        private readonly CategoryRepository $categoryRepository,
        private readonly OperationRepository $operationRepository
    ) {
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page = 1,User $author): PaginationInterface
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

    public function findById(int $id): ?Category
    {
        return $this->categoryRepository->find($id);
    }

    public function save(Category $category): void
    {
        $this->categoryRepository->save($category);
    }

    public function delete(Category $category): void
    {
        $this->operationRepository->nullifyCategory($category);
        $this->categoryRepository->delete($category);
    }
}
