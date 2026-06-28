<?php

/**
 * This file is part of the SI project.
 *
 * (c) Students
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Tag entity.
 */
#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private ?User $author = null;

    #[ORM\ManyToMany(targetEntity: Operation::class, mappedBy: 'tags')]
    private Collection $operations;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->operations = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int|null Tag id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get name.
     *
     * @return string|null Tag name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param string|null $name Tag name
     *
     * @return $this Current instance, for method chaining
     */
    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get author.
     *
     * @return User|null Tag author
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Set author.
     *
     * @param User|null $author Tag author
     *
     * @return $this Current instance, for method chaining
     */
    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get operations using this tag.
     *
     * @return Collection<int, Operation> Operations collection
     */
    public function getOperations(): Collection
    {
        return $this->operations;
    }

    /**
     * Add operation to this tag.
     *
     * @param Operation $operation Operation entity
     *
     * @return $this Current instance, for method chaining
     */
    public function addOperation(Operation $operation): static
    {
        if ($this->operations->contains($operation)) {
            $this->operations->add($operation);
            $operation->addTag($this);
        }

        return $this;
    }

    /**
     * Remove operation from this tag.
     *
     * @param Operation $operation Operation entity
     *
     * @return $this Current instance, for method chaining
     */
    public function removeOperation(Operation $operation): static
    {
        if ($this->operations->contains($operation)) {
            $operation->removeTag($this);
        }

        return $this;
    }
}
