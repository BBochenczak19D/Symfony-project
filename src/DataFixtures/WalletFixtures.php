<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Wallet;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class WalletFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    protected function loadData(): void
    {
        $currencies = ['PLN', 'EUR', 'USD', 'GBP'];
        $names = ['Oszczędności', 'Karta Visa', 'Gotówka'];

        $users = $this->manager->getRepository(User::class)->findAll(); //zwraca tablicę wszystkich userów z tabeli users

        if (empty($users)) {
            return;
        }

        for ($i = 0; $i < 3; ++$i) {
            $wallet = new Wallet();
            $wallet->setName($names[$i]);
            $wallet->setBalance((string) $this->faker->randomFloat(2, 100, 10000));
            $wallet->setCurrency($currencies[$i]);
            $wallet->setAuthor($this->faker->randomElement($users)); //osuje jednego usera z tej tablicy i przypisuje do portfela
            $this->manager->persist($wallet);
        }

        $this->manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
