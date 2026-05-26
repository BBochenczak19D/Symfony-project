<?php

/**
 * Operation service interface.
 */

namespace App\Service;

use App\Entity\Operation;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface OperationServiceInterface.
 */
interface OperationServiceInterface
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
     * @return Operation|null
     */
    public function findById(int $id): ?Operation;

    /**
     * @param Operation $operation
     * @return void
     */
    public function save(Operation $operation): void;

}
