<?php
/**
 * This file is part of the SI project.
 *
 * (c) Students
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace App\DTO;

/**
 *
 */
class WalletOperationDTO
{
    private string $id;
    private string $name;
    private string $amount;
    private string $currency;

    /**
     * @param string $id
     * @param string $name
     * @param string $amount
     * @param string $currency
     */
    public function __construct(string $id, string $name, string $amount, string $currency)
    {
        $this->id = $id;
        $this->name = $name;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }
}
