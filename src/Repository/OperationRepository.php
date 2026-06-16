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
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Operation::class);
    }

    /**
     * @return QueryBuilder
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
     * @param int                     $walletId
     * @param OperationListFiltersDTO $filters
     *
     * @return QueryBuilder
     */
    public function queryByWallet(int $walletId, OperationListFiltersDTO $filters): QueryBuilder
    {
        $queryBuilder = $this->queryAll()
            ->andWhere('operation.wallet = :walletId')
            ->setParameter('walletId', $walletId);

        return $this->applyFiltersToList($queryBuilder, $filters);
    }

    /**
     * @param Operation $operation
     *
     * @return void
     */
    public function save(Operation $operation): void
    {
        $this->getEntityManager()->persist($operation);
        $this->getEntityManager()->flush();
    }

    /**
     * @param Operation $operation
     *
     * @return void
     */
    public function delete(Operation $operation): void
    {
        $this->getEntityManager()->remove($operation);
        $this->getEntityManager()->flush();
    }

    /**
     * @return array
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
     * @param Category $category
     *
     * @return void
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
     * @param QueryBuilder            $queryBuilder
     * @param OperationListFiltersDTO $filters
     *
     * @return QueryBuilder
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

        return $queryBuilder;
    }
}
