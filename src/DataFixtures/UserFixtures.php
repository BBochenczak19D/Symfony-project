<?php

namespace App\DataFixtures;

use App\Entity\Enum\UserRole;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends AbstractBaseFixtures
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    protected function loadData(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail(sprintf('user%d@example.com', $i));
            $user->setRoles([UserRole::ROLE_USER->value]);
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'user1234')
            );
            $this->manager->persist($user);
        }

        for ($i = 0; $i < 3; $i++) {
            $user = new User();
            $user->setEmail(sprintf('admin%d@example.com', $i));
            $user->setRoles([UserRole::ROLE_USER->value, UserRole::ROLE_ADMIN->value]);
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'admin1234')
            );
            $this->manager->persist($user);
        }

        $this->manager->flush();
    }
}
