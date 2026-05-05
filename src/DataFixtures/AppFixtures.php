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

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Application fixtures loader.
 */
class AppFixtures extends Fixture
{
    /**
     * Load fixtures into the database.
     *
     * @param ObjectManager $manager the object manager
     */
    public function load(ObjectManager $manager): void
    {
        $manager->flush();
    }
}
