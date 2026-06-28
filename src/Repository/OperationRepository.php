<?php

/**
 * This file is part of the SI project.
 *
 * (c) Students
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\DTO\OperationListFiltersDTO;
use App\DTO\WalletOperationDTO;
use App\Entity\Category;
use App\Entity\Operation;
use App\Entity\Tag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Operation>
 */
class OperationRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Operation::class);
    }

    /**
     * Query all operations with joined wallet, category and tags.
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(): QueryBuilder
    {
        return $this->createQueryBuilder('operation')
            ->leftJoin('operation.wallet', 'wallet')
            ->leftJoin('operation.category', 'category')
            ->leftJoin('operation.tags', 'tags')
            ->addSelect('wallet')
            ->addSelect('category')
            ->addSelect('tags');
    }

    /**
     * Query operations for given wallet, with filters applied.
     *
     * @param int                     $walletId Wallet id
     * @param OperationListFiltersDTO $filters  Filters
     *
     * @return QueryBuilder Query builder
     */
    public function queryByWallet(int $walletId, OperationListFiltersDTO $filters): QueryBuilder
    {
        $queryBuilder = $this->queryAll()
            ->andWhere('operation.wallet = :walletId')
            ->setParameter('walletId', $walletId);

        return $this->applyFiltersToList($queryBuilder, $filters);
    }

    /**
     * Save operation.
     *
     * @param Operation $operation Operation entity
     */
    public function save(Operation $operation): void
    {
        $this->getEntityManager()->persist($operation);
        $this->getEntityManager()->flush();
    }

    /**
     * Delete operation.
     *
     * @param Operation $operation Operation entity
     */
    public function delete(Operation $operation): void
    {
        $this->getEntityManager()->remove($operation);
        $this->getEntityManager()->flush();
    }

    /**
     * Find wallet operation totals grouped by wallet.
     *
     * @return WalletOperationDTO[] List of wallet operation totals
     */
    public function findByExampleField(): array
    {
        return $this->createQueryBuilder('o')
            ->select(sprintf(
                'NEW %s(w.id,w.name, SUM(o.amount), w.currency)',
                WalletOperationDTO::class
            ))
            ->leftJoin('o.wallet', 'w')
            ->groupBy('w.id')
            ->addGroupBy('w.name')
            ->addGroupBy('w.currency')
            ->getQuery()
            ->getResult();
    }

    /**
     * Sum operation amounts for given wallet within an optional date period.
     *
     * @param int                     $walletId Wallet id
     * @param \DateTimeImmutable|null $dateFrom Date from
     * @param \DateTimeImmutable|null $dateTo   Date to
     *
     * @return float Sum of amounts
     */
    public function sumByWalletAndPeriod(int $walletId, ?\DateTimeImmutable $dateFrom, ?\DateTimeImmutable $dateTo): float
    {
        $qb = $this->createQueryBuilder('o')
            ->select('SUM(o.amount)')
            ->andWhere('o.wallet = :walletId')
            ->setParameter('walletId', $walletId);

        if ($dateFrom instanceof \DateTimeImmutable) {
            $qb->andWhere('o.createdAt >= :dateFrom')->setParameter('dateFrom', $dateFrom);
        }

        if ($dateTo instanceof \DateTimeImmutable) {
            $qb->andWhere('o.createdAt <= :dateTo')->setParameter('dateTo', $dateTo);
        }

        return (float) ($qb->getQuery()->getSingleScalarResult() ?? 0);
    }

    /**
     * Nullify category on all operations referencing it.
     *
     * @param Category $category Category entity
     */
    public function nullifyCategory(Category $category): void
    {
        $this->createQueryBuilder('o')
            ->update()
            ->set('o.category', ':null')
            ->where('o.category = :category')
            ->setParameter('null', null)
            ->setParameter('category', $category)
            ->getQuery()
            ->execute();
    }

    /**
     * Apply filters to given query builder.
     *
     * @param QueryBuilder            $queryBuilder Query builder
     * @param OperationListFiltersDTO $filters      Filters
     *
     * @return QueryBuilder Query builder with filters applied
     */
    private function applyFiltersToList(QueryBuilder $queryBuilder, OperationListFiltersDTO $filters): QueryBuilder
    {
        if ($filters->category instanceof Category) {
            $queryBuilder->andWhere('category = :category')
                ->setParameter('category', $filters->category);
        }

        if ($filters->tag instanceof Tag) {
            $queryBuilder->andWhere('tags IN (:tag)')
                ->setParameter('tag', $filters->tag);
        }

        if ($filters->dateFrom instanceof \DateTimeImmutable) {
            $queryBuilder->andWhere('operation.createdAt >= :dateFrom')
                ->setParameter('dateFrom', $filters->dateFrom);
        }

        if ($filters->dateTo instanceof \DateTimeImmutable) {
            $queryBuilder->andWhere('operation.createdAt <= :dateTo')
                ->setParameter('dateTo', $filters->dateTo);
        }

        return $queryBuilder;
    }
}
