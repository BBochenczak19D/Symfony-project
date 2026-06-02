<?php

/**
 * Tag service interface.
 */

namespace App\Service;

use App\Entity\Tag;
use App\Entity\User;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Interface TagServiceInterface.
 */
interface TagServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page, User $author): PaginationInterface;

    public function findById(int $id): ?Tag;

    public function save(Tag $tag): void;

    public function delete(Tag $tag): void;

    public function findOneByName(string $name): ?Tag;

}
