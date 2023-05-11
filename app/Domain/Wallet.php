<?php

namespace App\Domain;

class Wallet
{
    private int $id;
    private array $buyedCoins;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->buyedCoins = [];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNumOfBuyedCoins(): int
    {
        return count($this->buyedCoins);
    }

    public function buy(Coin $coin): void
    {
        $this->buyedCoins[] = $coin;
    }
}
