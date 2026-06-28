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
 * DTO representing a wallet operation.
 */
class WalletOperationDTO
{
    private string $id;
    private string $name;
    private string $amount;
    private string $currency;

    /**
     * Constructor.
     *
     * @param string $id       Wallet id
     * @param string $name     Wallet name
     * @param string $amount   Total amount
     * @param string $currency Wallet currency
     */
    public function __construct(string $id, string $name, string $amount, string $currency)
    {
        $this->id = $id;
        $this->name = $name;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    /**
     * Get id.
     *
     * @return string Wallet id
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Get name.
     *
     * @return string Wallet name
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Get amount.
     *
     * @return string Total amount
     */
    public function getAmount(): string
    {
        return $this->amount;
    }

    /**
     * Get currency.
     *
     * @return string Wallet currency
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }
}
