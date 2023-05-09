<?php

namespace Tests\app\Infrastructure\Controller;

use App\Infrastructure\Persistence\WalletDataSource;
use Mockery;
use Tests\TestCase;

class SellCoinControllerTest extends TestCase
{
    private WalletDataSource $walletDataSource;

    protected function setUp(): void
    {
        parent::setUp();

        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
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
            'coin_id' => 'asdf',
            'wallet_id' => $walletId,
            'amount_usd' => 4]);

        $response->assertStatus(404);
        $response->assertExactJson([]);
    }
}
