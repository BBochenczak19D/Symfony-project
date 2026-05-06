<?php

namespace App\Dto;

class WalletOperationDto
{
    private string $id;
    private string $name;
    private string $amount;
    private string $currency;

    public function __construct(string $id, string $name, string $amount, string $currency)
    {
        $this->id = $id;
        $this->name = $name;
        $this->amount = $amount;
        $this->currency = $currency;
    }
    public function getId(): string
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
