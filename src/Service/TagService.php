<?php

namespace App\Service;

use App\Entity\Tag;
use App\Entity\User;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

class TagService implements TagServiceInterface
{
    private const int PAGINATOR_ITEMS_PER_PAGE = 5;

    public function __construct(
        private readonly PaginatorInterface $paginator,
        private readonly TagRepository $tagRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
    }

    /**
     * Get paginated list.
     *
     * @param int $page Page number
     * @param User $author
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page = 1,User $author): PaginationInterface
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

    public function findById(int $id): ?Tag
    {
        return $this->tagRepository->find($id);
    }
    public function findOneById(int $id): ?Tag
    {
        return $this->tagRepository->findOneById($id);
    }

    public function save(Tag $tag): void
    {
        $this->entityManager->persist($tag);
        $this->entityManager->flush();
    }

    public function delete(Tag $tag): void
    {
        $this->entityManager->remove($tag);
        $this->entityManager->flush();
    }

    public function findOneByName(string $name): ?Tag
    {
        return $this->tagRepository->findOneByName($name);
    }
}
