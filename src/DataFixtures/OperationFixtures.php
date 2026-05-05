<?php

namespace App\DataFixtures;

use App\Entity\Operation;
use App\Entity\Wallet;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

// USUNIĘTO 'abstract' i zmieniono rozszerzenie na AbstractBaseFixtures
class OperationFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Główna logika ładowania danych.
     */
    public function loadData(): void
    {
        // Pobieramy portfele przez managera dostępnego w klasie bazowej
        $wallets = $this->manager->getRepository(Wallet::class)->findAll();

        if (empty($wallets)) {
            return;
        }

        for ($i = 0; $i < 20; ++$i) {
            $operation = new Operation();

            $operation->setAmount((string) $this->faker->randomFloat(2, -500, 1000));

            $operation->setCreatedAt(
                \DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween('-1 month'))
            );

            $operation->setDescription($this->faker->sentence(3));

            $randomWallet = $this->faker->randomElement($wallets);
            $operation->setWallet($randomWallet);

            $this->manager->persist($operation);
        }

        $this->manager->flush();
    }

    /**
     * Dzięki temu Symfony najpierw załaduje portfele, a potem operacje.
     */
    public function getDependencies(): array
    {
        return [
            WalletFixtures::class,
        ];
    }
}
