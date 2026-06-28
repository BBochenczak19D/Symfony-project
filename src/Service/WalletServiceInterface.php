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

use App\DTO\OperationListInputFiltersDTO;
use App\Entity\User;
use App\Entity\Wallet;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Wallet service interface.
 */
interface WalletServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param User $author Wallet author
     * @param int  $page   Page number
     *
     * @return PaginationInterface Paginated list
     */
    public function getPaginatedList(User $author, int $page = 1): PaginationInterface;

    /**
     * Get operation totals.
     *
     * @return array Aggregated operation totals
     */
    public function getOperationTotals(): array;

    /**
     * Find wallet by id.
     *
     * @param int $id Wallet id
     *
     * @return Wallet|null Found wallet or null
     */
    public function findById(int $id): ?Wallet;

    /**
     * Get paginated operations for given wallet.
     *
     * @param int                          $walletId Wallet id
     * @param int                          $page     Page number
     * @param OperationListInputFiltersDTO $filters  Filters
     *
     * @return PaginationInterface Paginated list
     */
    public function getPaginatedOperations(int $walletId, int $page, OperationListInputFiltersDTO $filters): PaginationInterface;

    /**
     * Save wallet.
     *
     * @param Wallet $wallet Wallet entity
     */
    public function save(Wallet $wallet): void;

    /**
     * Delete wallet.
     *
     * @param Wallet $wallet Wallet entity
     */
    public function delete(Wallet $wallet): void;

    /**
     * Edit wallet.
     *
     * @param Wallet $wallet Wallet entity
     */
    public function editWallet(Wallet $wallet): void;

    /**
     * Get current balance for given wallet.
     *
     * @param int $walletId Wallet id
     *
     * @return float Current balance
     */
    public function getCurrentBalance(int $walletId): float;

    /**
     * Check if given amount can be added to wallet without exceeding limits.
     *
     * @param int        $walletId  Wallet id
     * @param float      $newAmount New amount
     * @param float|null $oldAmount Previous amount, if editing an existing operation
     *
     * @return bool True if amount can be added
     */
    public function canAddAmount(int $walletId, float $newAmount, ?float $oldAmount = null): bool;

    /**
     * Get balance for given wallet within an optional date period.
     *
     * @param int                     $walletId Wallet id
     * @param \DateTimeImmutable|null $dateFrom Date from
     * @param \DateTimeImmutable|null $dateTo   Date to
     *
     * @return float Period balance
     */
    public function getPeriodBalance(int $walletId, ?\DateTimeImmutable $dateFrom, ?\DateTimeImmutable $dateTo): float;
}
