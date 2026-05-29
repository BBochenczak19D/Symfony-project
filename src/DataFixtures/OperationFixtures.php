<?php

namespace App\DataFixtures;

use App\Entity\Operation;
use App\Entity\Wallet;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 *
 */
class OperationFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{

    /**
     * @return void
     */
    public function loadData(): void
    {
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
     * @return \class-string[]
     */
    public function getDependencies(): array
    {
        return [
            WalletFixtures::class,
        ];
    }
}
