<?php

namespace App\Repository;

use App\DTO\WalletOperationDTO;
use App\Entity\Operation;
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
            ->addSelect('wallet');
    }

    /**
     * @param int $walletId
     * @return QueryBuilder
     */
    public function queryByWallet(int $walletId): QueryBuilder
    {
        return $this->queryAll()
            ->andWhere('operation.wallet = :walletId')
            ->setParameter('walletId', $walletId);
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
}
