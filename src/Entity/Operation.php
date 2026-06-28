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

use App\Repository\OperationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Operation entity representing a single wallet transaction.
 */
#[ORM\Entity(repositoryClass: OperationRepository::class)]
class Operation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Assert\NotBlank]
    private ?string $amount = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Wallet $wallet = null;

    #[ORM\ManyToOne(targetEntity: Category::class)]
    #[ORM\JoinColumn(nullable: true)] // wskauzjemy jak moze wygladac ta kolumna, moze byc pusta
    private ?Category $category = null;

    /**
     * @var Collection<int, Tag>
     */
    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'operations')]
    private Collection $tags;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->tags = new ArrayCollection();
    }

    /**
     * Get id.
     *
     * @return int|null Operation id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get amount.
     *
     * @return string|null Operation amount
     */
    public function getAmount(): ?string
    {
        return $this->amount;
    }

    /**
     * Set amount.
     *
     * @param string $amount Operation amount
     *
     * @return $this Current instance, for method chaining
     */
    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get creation date.
     *
     * @return \DateTimeImmutable|null Creation date
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Set creation date.
     *
     * @param \DateTimeImmutable $createdAt Creation date
     *
     * @return $this Current instance, for method chaining
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string|null Operation description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set description.
     *
     * @param string|null $description Operation description
     *
     * @return $this Current instance, for method chaining
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get wallet.
     *
     * @return Wallet|null Wallet that this operation belongs to
     */
    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    /**
     * Set wallet.
     *
     * @param Wallet|null $wallet Wallet that this operation belongs to
     *
     * @return $this Current instance, for method chaining
     */
    public function setWallet(?Wallet $wallet): static
    {
        $this->wallet = $wallet;

        return $this;
    }

    /**
     * Get category.
     *
     * @return Category|null Operation category
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * Set category.
     *
     * @param Category|null $category Operation category
     *
     * @return $this Current instance, for method chaining
     */
    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get tags.
     *
     * @return Collection<int, Tag> Tags assigned to this operation
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * Add tag.
     *
     * @param Tag $tag Tag entity
     *
     * @return $this Current instance, for method chaining
     */
    public function addTag(Tag $tag): static
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    /**
     * Remove tag.
     *
     * @param Tag $tag Tag entity
     *
     * @return $this Current instance, for method chaining
     */
    public function removeTag(Tag $tag): static
    {
        $this->tags->removeElement($tag);

        return $this;
    }
}
