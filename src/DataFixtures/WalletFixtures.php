<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Wallet;


class WalletFixtures extends AbstractBaseFixtures
{
    protected function loadData(): void
    {
        $currencies = ['PLN', 'EUR', 'USD', 'GBP'];
        $names = ['Oszczędności', 'Karta Visa', 'Gotówka'];

        for ($i = 0; $i < 3; ++$i) {
            $wallet = new Wallet();
            $wallet->setName($names[$i]);
            $wallet->setBalance((string) $this->faker->randomFloat(2, 100, 10000));
            $wallet->setCurrency($currencies[$i]);
            $this->manager->persist($wallet);
        }

        $this->manager->flush();
    }
}
