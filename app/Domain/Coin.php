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
}
