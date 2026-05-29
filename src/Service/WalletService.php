<?php

/**
 * Wallet service.
 */

namespace App\Service;

use App\Entity\Operation;
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
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See https://symfony.com/doc/current/best_practices.html#configuration
     *
     * @varant int
     */
    private const int PAGINATOR_ITEMS_PER_PAGE = 7;

    /**
     * Constructor.
     *
     * @param WalletRepository   $walletRepository Task repository
     * @param PaginatorInterface $paginator        Paginator
     */
    public function __construct(
        private readonly WalletRepository $walletRepository,
        private readonly PaginatorInterface $paginator,
        private readonly OperationRepository $operationRepository)
    {
    }

    /**
     * Get paginated list.
     *
     * @param int|null $page Page number
     *
     * @return PaginationInterface Paginated list
     */
    public function getPaginatedList(?int $page = 1): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->walletRepository->queryAll(),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE,
            [
                'sortFieldAllowList' => ['wallet.id', 'wallet.name', 'wallet.balance', 'wallet.currency'],
                'defaultSortFieldName' => 'wallet.name',
                'defaultSortDirection' => 'desc',
            ]
        );
    }

    public function getOperationTotals(): array
    {
        $totals = [];
        foreach ($this->operationRepository->findByExampleField() as $dto) {
            $totals[$dto->getId()] = $dto->getAmount();
        }

        return $totals;
    }

    public function findById(int $id): ?Wallet
    {
        return $this->walletRepository->find($id);
    }

    public function getPaginatedOperations(int $walletId, int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->operationRepository->queryByWallet($walletId),
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
     * @param wWllet $operation
     * @return void
     */
    public function save(Wallet $wallet): void
    {
        $this->entityManager->persist($wallet);
        $this->entityManager->flush();
    }

    /**
     * @param Operation|Wallet $operation
     * @return void
     */
    public function delete(Operation|Wallet $operation): void
    {
        $this->operationRepository->delete($operation);
    }
}
