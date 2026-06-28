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

use App\Entity\Operation;
use App\Repository\OperationRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class OperationService.
 */
class OperationService implements OperationServiceInterface
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
     * @param OperationRepository $operationRepository Operation repository
     */
    public function __construct(private readonly PaginatorInterface $paginator, private readonly OperationRepository $operationRepository)
    {
    }

    /**
     * Get paginated list.
     *
     * @param int|null $page Page number
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
     * Find operation by ID.
     *
     * @param int $id Operation ID
     *
     * @return Operation|null Operation entity
     */
    public function findById(int $id): ?Operation
    {
        return $this->operationRepository->find($id);
    }

    /**
     * Save operation.
     *
     * @param Operation $operation Operation entity
     */
    public function save(Operation $operation): void
    {
        $operation->setCreatedAt(new \DateTimeImmutable());
        if (null === $operation->getId()) {
            $operation->setCreatedAt(new \DateTimeImmutable());
        }
        $this->operationRepository->save($operation);
    }

    /**
     * Delete operation.
     *
     * @param Operation $operation Operation entity
     */
    public function delete(Operation $operation): void
    {
        $this->operationRepository->delete($operation);
    }
}
