<?php

namespace Tests\app\Infrastructure\Controller;

use App\Infrastructure\Persistence\CoinDataSource;
use Tests\TestCase;
use Mockery;
use Exception;

class BuyCoinControllerTest extends TestCase
{
    private CoinDataSource $coinDataSource;
    protected function setUp(): void
    {
        parent::setUp();

        $this->coinDataSource = Mockery::mock(CoinDataSource::class);
        $this->app->bind(CoinDataSource::class, function () {
            return $this->coinDataSource;
        });
    }

    /**
     * @test
     */
    public function buyCoinBodyParameterRequestError()
    {
        $response = $this->postJson(
            '/api/coin/buy',
            ['id_coin' => 'sttring',
             'id_wallet' => 'sfdfdf',
            'amount_usd' => 1]
        );

        $response->assertStatus(400);
        $response->assertExactJson([]);
    }

    /**
     * @test
     */
    public function buyCoinBodyParameterRequestTypeInvalid()
    {
        $response = $this->postJson(
            '/api/coin/buy',
            ['coin_id' => 2,
                'wallet_id' => 'sfdfdf',
                'amount_usd' => 1]
        );

        $response->assertStatus(400);
        $response->assertExactJson([]);
    }

    /**
     * @test
     */
    public function buyCoinNotFoundError()
    {
        $this->coinDataSource
            ->expects("getCoinById")
            ->with('c_000001')
            ->andReturnNull();

        $response = $this->postJson(
            '/api/coin/buy',
            ['coin_id' => "c_000001",
                'wallet_id' => 'w_000001',
                'amount_usd' => 1]
        );

        $response->assertStatus(404);
        $response->assertExactJson(["Moneda no encontrada"]);
    }
}
