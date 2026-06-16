<?php

/**
 * This file is part of the SI project.
 *
 * (c) Students
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Service;

use App\Entity\Tag;
use App\Entity\User;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class TagService.
 */
class TagService implements TagServiceInterface
{
    /**
     * Items per page.
     *
     * @var int
     */
    private const int PAGINATOR_ITEMS_PER_PAGE = 5;

    /**
     * Constructor.
     *
     * @param PaginatorInterface     $paginator     Paginator
     * @param TagRepository          $tagRepository Tag repository
     * @param EntityManagerInterface $entityManager Entity manager
     */
    public function __construct(private readonly PaginatorInterface $paginator, private readonly TagRepository $tagRepository, private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * Get paginated list.
     *
     * @param User $author Author
     * @param int  $page   Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(User $author, int $page = 1): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->tagRepository->queryAll($author),
            $page,
            self::PAGINATOR_ITEMS_PER_PAGE,
            [
                'sortFieldAllowList' => ['Tag.id', 'Tag.name'],
                'defaultSortFieldName' => 'Tag.name',
                'defaultSortDirection' => 'asc',
            ]
        );
    }

    /**
     * Find tag by ID.
     *
     * @param int $id Tag ID
     *
     * @return Tag|null Tag entity
     */
    public function findById(int $id): ?Tag
    {
        return $this->tagRepository->find($id);
    }

    /**
     * Find one tag by ID.
     *
     * @param int $id Tag ID
     *
     * @return Tag|null Tag entity
     */
    public function findOneById(int $id): ?Tag
    {
        return $this->tagRepository->findOneById($id);
    }

    /**
     * Save tag.
     *
     * @param Tag $tag Tag entity
     */
    public function save(Tag $tag): void
    {
        $this->entityManager->persist($tag);
        $this->entityManager->flush();
    }

    /**
     * Delete tag.
     *
     * @param Tag $tag Tag entity
     */
    public function delete(Tag $tag): void
    {
        $this->entityManager->remove($tag);
        $this->entityManager->flush();
    }

    /**
     * Find one tag by name.
     *
     * @param string $name Tag name
     *
     * @return Tag|null Tag entity
     */
    public function findOneByName(string $name): ?Tag
    {
        return $this->tagRepository->findOneByName($name);
    }
}
