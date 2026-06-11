<?php

/**
 * Wallet service interface.
 */

namespace App\Service;
use App\Entity\User;
use App\Entity\Wallet;
use Knp\Component\Pager\Pagination\PaginationInterface;
use App\DTO\OperationListInputFiltersDTO;

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
    public function getPaginatedList(int $page, User $author): PaginationInterface;

    public function getOperationTotals(): array;

    public function findById(int $id): ?Wallet;

    public function getPaginatedOperations(int $walletId, int $page, OperationListInputFiltersDTO $filters): PaginationInterface;

    public function save(Wallet $wallet): void;
    public function delete(Wallet $wallet): void;
    public function editWallet(Wallet $wallet): void;
    public function getCurrentBalance(int $walletId): float;
    public function canAddAmount(int $walletId, float $newAmount, ?float $oldAmount = null): bool;
}
