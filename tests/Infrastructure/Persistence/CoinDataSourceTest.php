<?php

namespace Tests\Infrastructure\Persistence;

use App\Domain\Coin;
use App\Infrastructure\ApiManager;
use App\Infrastructure\Persistence\CoinDataSource;
use PHPUnit\Framework\TestCase;
use Mockery;

class CoinDataSourceTest extends TestCase
{
    /**
     * @test
     */
    public function returnNullIfIdInvalid()
    {
        $apiManager = Mockery::mock(ApiManager::class);
        $apiManager->expects("getCoin")
                    ->with(-1)
                    ->once()
                    ->andReturn("[]");

        $bad_id = -1;
        $coinDataSource = new CoinDataSource($apiManager);

        $response = $coinDataSource->searchCoin($bad_id);

        $this->assertNull($response);
    }

    /**
     * @test
     */
    public function returnUserIfIdIsValid()
    {
        $apiManager = Mockery::mock(ApiManager::class);
        $apiManager->expects("getCoin")
            ->with(3)
            ->once()
            ->andReturn('[
                    {
                        "id": "3",
                        "symbol": "VTC",
                        "name": "Vertcoin",
                        "price_usd": "0.081965"
                    }
                ]');

        $coin = new Coin("3", "Vertcoin", "VTC", 0, 0.081965);
        $coin_id = 3;
        $coinDataSource = new CoinDataSource($apiManager);

        $response = $coinDataSource->searchCoin($coin_id);

        $this->assertEquals($response, $coin);
    }
}
