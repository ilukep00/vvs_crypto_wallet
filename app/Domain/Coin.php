<?php

namespace App\Domain;

class Coin
{
    public string $id;
    public string $name;
    public string $symbol;
    public float $ammount;
    public float $invertedMoney;

    public function __construct(
        string $id = '',
        string $name = '',
        string $symbol = '',
        float $ammount = 0,
        float $invertedMoney = 0
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->symbol = $symbol;
        $this->ammount = $ammount;
        $this->invertedMoney = $invertedMoney;
    }

    public function add(Coin $coinToAdd): Coin
    {
        $this->ammount = $this->ammount + $coinToAdd->ammount;
        $this->invertedMoney = $this->invertedMoney + $coinToAdd->invertedMoney;

        return $this;
    }

    /**
     * @return float|int
     */
    public function getAmmount(): float|int
    {
        return $this->ammount;
    }

    /**
     * @param float|int $ammount
     */
    public function setAmmount(float|int $ammount): void
    {
        $this->ammount = $ammount;
    }

    /**
     * @return float|int
     */
    public function getInvertedMoney(): float|int
    {
        return $this->invertedMoney;
    }

    /**
     * @param float|int $invertedMoney
     */
    public function setInvertedMoney(float|int $invertedMoney): void
    {
        $this->invertedMoney = $invertedMoney;
    }


}
