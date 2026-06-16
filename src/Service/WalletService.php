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

use App\DTO\OperationListFiltersDTO;
use App\DTO\OperationListInputFiltersDTO;
use App\Entity\Operation;
use App\Entity\User;
use App\Entity\Wallet;
use App\Repository\OperationRepository;
use App\Repository\WalletRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class WalletService.
 */
class WalletService implements WalletServiceInterface
{
    /**
     * Items per page.
     *
     * @var int
     */
    private const int PAGINATOR_ITEMS_PER_PAGE = 7;

    /**
     * Constructor.
     *
     * @param CategoryServiceInterface $categoryService     Category service
     * @param TagServiceInterface      $tagService          Tag service
     * @param WalletRepository         $walletRepository    Wallet repository
     * @param PaginatorInterface       $paginator           Paginator
     * @param OperationRepository      $operationRepository Operation repository
     */
    public function __construct(private readonly CategoryServiceInterface $categoryService, private readonly TagServiceInterface $tagService, private readonly WalletRepository $walletRepository, private readonly PaginatorInterface $paginator, private readonly OperationRepository $operationRepository)
    {
    }

    /**
     * Get paginated list.
     *
     * @param User     $author Author
     * @param int|null $page   Page number
     *
     * @return PaginationInterface Paginated list
     */
    public function getPaginatedList(User $author, int $page = 1): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->walletRepository->queryAll($author),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE,
            [
                'sortFieldAllowList' => ['wallet.id', 'wallet.name', 'wallet.balance', 'wallet.currency'],
                'defaultSortFieldName' => 'wallet.name',
                'defaultSortDirection' => 'desc',
            ]
        );
    }

    /**
     * Get operation totals.
     *
     * @return array Operation totals
     */
    public function getOperationTotals(): array
    {
        $totals = [];
        foreach ($this->operationRepository->findByExampleField() as $dto) {
            $totals[$dto->getId()] = $dto->getAmount();
        }

        return $totals;
    }

    /**
     * Find wallet by ID.
     *
     * @param int $id Wallet ID
     *
     * @return Wallet|null Wallet entity
     */
    public function findById(int $id): ?Wallet
    {
        return $this->walletRepository->find($id);
    }

    /**
     * Get paginated operations for a wallet.
     *
     * @param int                          $walletId Wallet ID
     * @param int                          $page     Page number
     * @param OperationListInputFiltersDTO $filters  Filters
     *
     * @return PaginationInterface Paginated list
     */
    public function getPaginatedOperations(int $walletId, int $page, OperationListInputFiltersDTO $filters): PaginationInterface
    {
        $preparedFilters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->operationRepository->queryByWallet($walletId, $preparedFilters),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE,
            [
                'sortFieldAllowList' => ['operation.amount', 'operation.createdAt', 'operation.description'],
                'defaultSortFieldName' => 'operation.createdAt',
                'defaultSortDirection' => 'desc',
            ]
        );
    }

    /**
     * Save wallet.
     *
     * @param Wallet $wallet Wallet entity
     */
    public function save(Wallet $wallet): void
    {
        $this->walletRepository->save($wallet);
    }

    /**
     * Delete operation or wallet.
     *
     * @param Operation|Wallet $operation Operation or wallet entity
     */
    public function delete(Operation|Wallet $operation): void
    {
        $this->operationRepository->delete($operation);
    }

    /**
     * Get current balance for a wallet.
     *
     * @param int $walletId Wallet ID
     *
     * @return float Current balance
     */
    public function getCurrentBalance(int $walletId): float
    {
        $totals = $this->getOperationTotals();

        return (float) ($totals[$walletId] ?? 0);
    }

    /**
     * Check if amount can be added to wallet.
     *
     * @param int        $walletId  Wallet ID
     * @param float      $newAmount New amount
     * @param float|null $oldAmount Old amount
     *
     * @return bool True if amount can be added
     */
    public function canAddAmount(int $walletId, float $newAmount, ?float $oldAmount = null): bool
    {
        $current = $this->getCurrentBalance($walletId);
        $base = $current - ($oldAmount ?? 0);

        return $base + $newAmount >= 0;
    }

    /**
     * Edit wallet.
     *
     * @param Wallet $wallet Wallet entity
     */
    public function editWallet(Wallet $wallet): void
    {
        $this->walletRepository->save($wallet);
    }

    /**
     * Prepare filters.
     *
     * @param OperationListInputFiltersDTO $filters Input filters
     *
     * @return OperationListFiltersDTO Prepared filters
     */
    private function prepareFilters(OperationListInputFiltersDTO $filters): OperationListFiltersDTO
    {
        return new OperationListFiltersDTO(
            null !== $filters->categoryId ? $this->categoryService->findOneById($filters->categoryId) : null,
            null !== $filters->tagId ? $this->tagService->findOneById($filters->tagId) : null,
        );
    }
}
