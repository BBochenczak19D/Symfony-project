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
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * @param User $author
     *
     * @return QueryBuilder
     */
    public function queryAll(User $author): \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilder('category')
            ->where('category.author = :author OR category.author IS NULL')
            ->setParameter('author', $author);
    }

    /**
     * @param Category $category
     *
     * @return void
     */
    public function save(Category $category): void
    {
        $this->getEntityManager()->persist($category);
        $this->getEntityManager()->flush();
    }

    /**
     * @param Category $category
     *
     * @return void
     */
    public function delete(Category $category): void
    {
        $this->getEntityManager()->remove($category);
        $this->getEntityManager()->flush();
    }
}
