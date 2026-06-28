<?php

/**
 * This file is part of the SI project.
 *
 * (c) Students
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Category>
 */
class CategoryRepository extends ServiceEntityRepository
{
    /**
     * Constructor.
     *
     * @param ManagerRegistry $registry Manager registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * Query all categories visible to given author (own + global).
     *
     * @param User $author Category author
     *
     * @return QueryBuilder Query builder
     */
    public function queryAll(User $author): QueryBuilder
    {
        return $this->createQueryBuilder('category')
            ->where('category.author = :author OR category.author IS NULL')
            ->setParameter('author', $author);
    }

    /**
     * Save category.
     *
     * @param Category $category Category entity
     */
    public function save(Category $category): void
    {
        $this->getEntityManager()->persist($category);
        $this->getEntityManager()->flush();
    }

    /**
     * Delete category.
     *
     * @param Category $category Category entity
     */
    public function delete(Category $category): void
    {
        $this->getEntityManager()->remove($category);
        $this->getEntityManager()->flush();
    }
}
