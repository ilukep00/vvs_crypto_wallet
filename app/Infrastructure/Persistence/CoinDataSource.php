<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Coin;
use App\Infrastructure\ApiManager;

class CoinDataSource
{
    private ApiManager $apiCalls;

    /**
     * @param ApiManager $apiCalls
     */
    public function __construct(ApiManager $apiManager)
    {
        $this->apiCalls = $apiManager;
    }

    public function searchCoin(string $coinId, int $amount_usd): Coin|null
    {
        $response = $this->apiCalls->getCoin($coinId);
        if ($response == "[]") {
            return null;
        }
        $response = json_decode($response);
        $coin = new Coin(
            $response[0]->id,
            $response[0]->name,
            $response[0]->symbol,
            $amount_usd,
            $response[0]->price_usd * $amount_usd
        );
        return $coin;
    }
}
