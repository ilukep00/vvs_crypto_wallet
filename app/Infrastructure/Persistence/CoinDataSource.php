<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Coin;

class CoinDataSource
{
    public function searchCoin(string $coinId): Coin|null
    {
        //busqueda Api
        return new Coin();
    }
}
