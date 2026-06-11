<?php

namespace App\Repository;

use App\DTO\OperationListFiltersDTO;
use App\DTO\WalletOperationDTO;
use App\Entity\Category;
use App\Entity\Operation;
use App\Entity\Tag;

use App\Repository\OperationRepository;
use App\Resolver\OperationListInputFiltersDtoResolver;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Operation>
 */
class OperationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Operation::class);
    }

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

    public function queryByWallet(int $walletId, OperationListFiltersDTO $filters): QueryBuilder
    {
        $queryBuilder = $this->queryAll()
            ->andWhere('operation.wallet = :walletId')
            ->setParameter('walletId', $walletId);

        return $this->applyFiltersToList($queryBuilder, $filters);
    }

    public function save(Operation $operation): void
    {
        $this->getEntityManager()->persist($operation);
        $this->getEntityManager()->flush();
    }

    public function delete(Operation $operation): void
    {
        $this->getEntityManager()->remove($operation);
        $this->getEntityManager()->flush();
    }

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
