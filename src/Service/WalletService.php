<?php

/**
 * Wallet service.
 */

namespace App\Service;

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
    private const int PAGINATOR_ITEMS_PER_PAGE = 2;

    /**
     * Constructor.
     *
     * @param WalletRepository   $walletRepository Task repository
     * @param PaginatorInterface $paginator        Paginator
     */
    public function __construct(private readonly WalletRepository $walletRepository, private readonly PaginatorInterface $paginator)
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
}
