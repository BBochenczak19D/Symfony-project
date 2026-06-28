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
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * Tag service interface.
 */
interface TagServiceInterface
{
    /**
     * Get paginated list.
     *
     * @param User $author Tag author
     * @param int  $page   Page number
     *
     * @return PaginationInterface Paginated list
     */
    public function getPaginatedList(User $author, int $page): PaginationInterface;

    /**
     * Find tag by id.
     *
     * @param int $id Tag id
     *
     * @return Tag|null Found tag or null
     */
    public function findById(int $id): ?Tag;

    /**
     * Find one tag by id.
     *
     * @param int $id Tag id
     *
     * @return Tag|null Found tag or null
     */
    public function findOneById(int $id): ?Tag;

    /**
     * Save tag.
     *
     * @param Tag $tag Tag entity
     */
    public function save(Tag $tag): void;

    /**
     * Delete tag.
     *
     * @param Tag $tag Tag entity
     */
    public function delete(Tag $tag): void;

    /**
     * Find one tag by name.
     *
     * @param string $name Tag name
     *
     * @return Tag|null Found tag or null
     */
    public function findOneByName(string $name): ?Tag;
}
