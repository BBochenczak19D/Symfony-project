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
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Operation service interface.
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
     * Find operation by id.
     *
     * @param int $id Operation id
     *
     * @return Operation|null Found operation or null
     */
    public function findById(int $id): ?Operation;

    /**
     * Save operation.
     *
     * @param Operation $operation Operation entity
     */
    public function save(Operation $operation): void;

    /**
     * Delete operation.
     *
     * @param Operation $operation Operation entity
     */
    public function delete(Operation $operation): void;
}
