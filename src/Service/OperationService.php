<?php

namespace App\Service;

use App\Entity\Operation;
use App\Repository\OperationRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 *
 */
class OperationService implements OperationServiceInterface
{
    private const int PAGINATOR_ITEMS_PER_PAGE =5;

    public function __construct(
        private readonly PaginatorInterface $paginator,
        private readonly OperationRepository $operationRepository,
    ) {
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(?int $page = 1): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->operationRepository->queryAll(),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE,
            [
                'sortFieldAllowList' => ['operation.id', 'operation.amount', 'operation.createdAt'],
                'defaultSortFieldName' => 'operation.createdAt',
                'defaultSortDirection' => 'desc',
            ]
        );
    }

    /**
     * @param int $id
     * @return Operation|null
     */
    public function findById(int $id): ?Operation
    {
        return $this->operationRepository->find($id);
    }
}
