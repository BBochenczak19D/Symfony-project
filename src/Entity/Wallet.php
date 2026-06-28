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

use App\Repository\WalletRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Wallet entity.
 */
#[ORM\Entity(repositoryClass: WalletRepository::class)]
#[ORM\Table(name: 'wallet')]
class Wallet
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Type('string')]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 100)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $balance = null;

    #[ORM\Column(length: 5)]
    #[Assert\NotBlank]
    private ?string $currency = null;

    #[ORM\ManyToOne(targetEntity: User::class, fetch: 'EXTRA_LAZY')]
    #[ORM\JoinColumn(nullable: true)]
    #[Assert\NotBlank]
    #[Assert\Type(User::class)]
    private ?User $author = null;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->balance = 0;
    }

    /**
     * Get id.
     *
     * @return int|null Wallet id
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get name.
     *
     * @return string|null Wallet name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param string $name Wallet name
     *
     * @return $this Current instance, for method chaining
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get balance.
     *
     * @return string|null Wallet balance
     */
    public function getBalance(): ?string
    {
        return $this->balance;
    }

    /**
     * Set balance.
     *
     * @param string $balance Wallet balance
     *
     * @return $this Current instance, for method chaining
     */
    public function setBalance(string $balance): static
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get currency.
     *
     * @return string|null Wallet currency
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * Set currency.
     *
     * @param string $currency Wallet currency
     *
     * @return $this Current instance, for method chaining
     */
    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get author.
     *
     * @return User|null Wallet author
     */
    public function getAuthor(): ?User
    {
        return $this->author;
    }

    /**
     * Set author.
     *
     * @param User|null $author Wallet author
     *
     * @return $this Current instance, for method chaining
     */
    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }
}
