<?php

/**
 * Wallet service interface.
 */

namespace App\Service;

use App\Entity\Wallet;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface WalletServiceInterface.
 */
interface WalletServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface;

    public function getOperationTotals(): array;

    public function findById(int $id): ?Wallet;

    public function getPaginatedOperations(int $walletId, int $page): PaginationInterface;

    public function save(Wallet $wallet): void;
    public function delete(Wallet $wallet): void;

}
