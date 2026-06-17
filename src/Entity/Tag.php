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
 *
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
     *
     */
    public function __construct()
    {
        $this->operations = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return $this
     */
    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * @param User|null $author
     *
     * @return $this
     */
    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getOperations(): Collection
    {
        return $this->operations;
    }

    /**
     * @param Operation $operation
     *
     * @return $this
     */
    public function addOperation(Operation $operation): static
    {
        if ($this->operations->contains($operation))
        {
            $this->operations->add($operation);
            $operation->addTag($this);
        }

        return $this;
    }

    /**
     * @param Operation $operation
     *
     * @return $this
     */
    public function removeOperation(Operation $operation): static
    {
        if ($this->operations->contains($operation))
        {
            $operation->removeTag($this);
        }

        return $this;
    }
}
