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

    public function getCoinById(int $id): Coin|null
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
}
