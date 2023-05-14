<?php

namespace Tests\app\Infrastructure\Controller;

use App\Infrastructure\Persistence\CoinDataSource;
use App\Infrastructure\Persistence\WalletDataSource;
use Mockery;
use Tests\TestCase;

class SellCoinControllerTest extends TestCase
{
    private WalletDataSource $walletDataSource;
    private CoinDataSource $coinDataSource;

    protected function setUp(): void
    {
        parent::setUp();

        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->coinDataSource = Mockery::mock(CoinDataSource::class);
        $this->app->bind(CoinDataSource::class, function () {
            return $this->coinDataSource;
        });
        $this->app->bind(WalletDataSource::class, function () {
            return $this->walletDataSource;
        });
    }

    /**
     * @test
     */
    public function returnsErrorOnInvalidRequest()
    {
        $response = $this->postJson('/api/coin/sell', [
            'coinid' => '',
            'wallet_id' => '',
            'amount_usd' => '']);

        $response->assertStatus(400);
        $response->assertExactJson([]);
    }

    /**
     * @test
     */
    public function returnsErrorOnWalletNotFound()
    {
        $walletId = 'id_invalido';
        $this->walletDataSource
            ->expects('searchWallet')
            ->with($walletId)
            ->andReturn(null);

        $response = $this->postJson('/api/coin/sell', [
            'coin_id' => 'coinId',
            'wallet_id' => $walletId,
            'amount_usd' => 4]);

        $response->assertStatus(404);
        $response->assertExactJson([]);
    }

    /**
     * @test
     */
    public function returnsErrorOnCoinNotFound()
    {
        $walletId = 'walletId';
        $this->walletDataSource
            ->expects('searchWallet')
            ->with($walletId)
            ->andReturn('ok');

        $coinId = 'id_invalido';
        $this->coinDataSource
            ->expects('searchCoin')
            ->with($coinId)
            ->andReturn(null);

        $response = $this->postJson('/api/coin/sell', [
            'coin_id' => $coinId,
            'wallet_id' => $walletId,
            'amount_usd' => 4]);

        $response->assertStatus(404);
        $response->assertExactJson([]);
    }
}
