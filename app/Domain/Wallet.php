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

    public function getCoinById(int $id): Coin|null
    {
        foreach ($this->buyedCoins as $actualIterationCoin) {
            if ($id == $actualIterationCoin->id) {
                return $actualIterationCoin;
            }
        }

        return null;
    }

    public function deleteCoinById(int $id): void
    {
        for ($index=0; $index<count($this->buyedCoins); $index++) {
            if ($id == $this->buyedCoins[$index]->id) {
                unset($this->buyedCoins[$index]);
            }
        }
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
