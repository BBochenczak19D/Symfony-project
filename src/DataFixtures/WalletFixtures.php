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

use App\Entity\User;
use App\Entity\Wallet;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 *
 */
class WalletFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Get dependencies.
     *
     * @return array<int, string> Dependencies
     */
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    /**
     * Load data fixtures with the passed EntityManager.
     */
    protected function loadData(): void
    {
        $currencies = ['PLN', 'EUR', 'USD', 'GBP'];
        $names = ['Oszczędności', 'Karta Visa', 'Gotówka'];
        $users = $this->manager->getRepository(User::class)->findAll();
        if (empty($users)) {
            return;
        }
        for ($i = 0; $i < 3; ++$i) {
            $wallet = new Wallet();
            $wallet->setName($names[$i]);
            $wallet->setBalance((string) $this->faker->randomFloat(2, 100, 10000));
            $wallet->setCurrency($currencies[$i]);
            $wallet->setAuthor($this->faker->randomElement($users));
            $this->manager->persist($wallet);
        }
        $this->manager->flush();
    }
}
