<?php

namespace App\Domain;

class Wallet
{
    private string $id;
    private array $buyedCoins;

    public function __construct(string $id)
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

    public function getCoinById(string $id): Coin|null
    {
        foreach ($this->buyedCoins as &$actualIterationCoin) {
            if ($id == $actualIterationCoin->id) {
                return $actualIterationCoin;
            }
        }

        return null;
    }

    public function buy(Coin $coin): Coin
    {
        foreach ($this->buyedCoins as &$actualIterationCoin) {
            if ($coin->id == $actualIterationCoin->id) {
                $actualIterationCoin->add($coin);
                return $actualIterationCoin;
            }
        }

        $this->buyedCoins[] = $coin;

        return $coin;
    }

    public function getCoinList(): array
    {
        return $this->buyedCoins;
    }
}
