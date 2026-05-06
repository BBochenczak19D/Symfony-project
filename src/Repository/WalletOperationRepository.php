<?php

namespace App\Repository;

use App\Entity\Wallet;
use Doctrine\ORM\Mapping as ORM;

// #[ORM\Entity(repositoryClass: WalletOperationRepository::class)]
class WalletOperationRepository extends ServiceEntityRepository
{

    public function findByExampleField($value): ?Wallet
    {
        $result = $this->createQueryBuilder('w')
            ->select(sprintf(
                'NEW %s(w.name, SUM(w.amount),w.currency)',
                WalletOperationRepository::class
            ))
            //->from(Wallet::class, 'w')
            ->leftJoin('w.incomes', 'i')
            // ->where('YEAR(i.when) = 2020')
            ->groupBy('w.name')
            ->getQuery()
            ->getResult();
    }
}
