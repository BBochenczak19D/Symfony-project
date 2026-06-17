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
 *
 */
interface TagServiceInterface
{
    /**
     * @param User $author
     * @param int  $page
     *
     * @return PaginationInterface
     */
    public function getPaginatedList(User $author, int $page): PaginationInterface;

    /**
     * @param int $id
     *
     * @return Tag|null
     */
    public function findById(int $id): ?Tag;

    /**
     * @param int $id
     *
     * @return Tag|null
     */
    public function findOneById(int $id): ?Tag;

    /**
     * @param Tag $tag
     *
     * @return void
     */
    public function save(Tag $tag): void;

    /**
     * @param Tag $tag
     *
     * @return void
     */
    public function delete(Tag $tag): void;

    /**
     * @param string $name
     *
     * @return Tag|null
     */
    public function findOneByName(string $name): ?Tag;
}
