<?php

/**
 * This file is part of the SI project.
 *
 * (c) Students
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\DataFixtures;

use App\Entity\Operation;
use App\Entity\Wallet;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class OperationFixtures.
 */
class OperationFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data fixtures with the passed EntityManager.
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
     * Get dependencies.
     *
     * @return array<int, string> Dependencies
     */
    public function getDependencies(): array
    {
        return [
            WalletFixtures::class,
        ];
    }
}
