<?php
/**
 * This file is part of the SI project.
 *
 * (c) Students
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * Wallet service interface.
 */

namespace App\Service;

use App\Entity\User;
use App\Entity\Wallet;
use Knp\Component\Pager\Pagination\PaginationInterface;
use App\DTO\OperationListInputFiltersDTO;


/**
 *
 */
interface WalletServiceInterface
{
    /**
     * @param User $author
     * @param int  $page
     *
     * @return PaginationInterface
     */
    public function getPaginatedList(User $author, int $page = 1): PaginationInterface;

    /**
     * @return array
     */
    public function getOperationTotals(): array;

    /**
     * @param int $id
     *
     * @return Wallet|null
     */
    public function findById(int $id): ?Wallet;

    /**
     * @param int                          $walletId
     * @param int                          $page
     * @param OperationListInputFiltersDTO $filters
     *
     * @return PaginationInterface
     */
    public function getPaginatedOperations(int $walletId, int $page, OperationListInputFiltersDTO $filters): PaginationInterface;

    /**
     * @param Wallet $wallet
     *
     * @return void
     */
    public function save(Wallet $wallet): void;

    /**
     * @param Wallet $wallet
     *
     * @return void
     */
    public function delete(Wallet $wallet): void;

    /**
     * @param Wallet $wallet
     *
     * @return void
     */
    public function editWallet(Wallet $wallet): void;

    /**
     * @param int $walletId
     *
     * @return float
     */
    public function getCurrentBalance(int $walletId): float;

    /**
     * @param int        $walletId
     * @param float      $newAmount
     * @param float|null $oldAmount
     *
     * @return bool
     */
    public function canAddAmount(int $walletId, float $newAmount, ?float $oldAmount = null): bool;
}
