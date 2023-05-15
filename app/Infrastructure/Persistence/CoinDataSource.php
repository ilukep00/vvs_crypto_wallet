<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Coin;
use App\Domain\User;
use App\Infrastructure\ApiManager;

class CoinDataSource
{
    private ApiManager $apiCalls;

    /**
     * @param ApiManager $apiCalls
     */
    public function __construct(ApiManager $apiCalls)
    {
        $this->apiCalls = $apiCalls;
    }

    public function searchCoin(string $coinId): Coin|null
    {
        $response = $this->apiCalls->getCoin($coinId);
        if ($response == "[]") {
            return null;
        }
        $response = json_decode($response);
        $coin = new Coin($response[0]->id, $response[0]->name, $response[0]->symbol, 0, $response[0]->price_usd);
        return $coin;
    }
}
