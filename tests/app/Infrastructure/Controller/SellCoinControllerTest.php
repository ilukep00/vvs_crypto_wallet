<?php

namespace Tests\app\Infrastructure\Controller;

use App\Domain\Coin;
use App\Domain\Wallet;
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
        $coinId = '1';

        $spectedWallet = Mockery::mock(Wallet::class);
        $spectedWallet->expects('getCoinById')
            ->with($coinId)
            ->andReturn(null);

        $this->walletDataSource
            ->expects('searchWallet')
            ->with($walletId)
            ->andReturn($spectedWallet);

        $response = $this->postJson('/api/coin/sell', [
            'coin_id' => $coinId,
            'wallet_id' => $walletId,
            'amount_usd' => 4]);

        $response->assertStatus(404);
        $response->assertExactJson([]);
    }

    /**
     * @test
     */
    public function returnsSellCoinSuccess()
    {
        $walletId = 'walletId';
        $coinId = '1';

        $spectedWallet = Mockery::mock(Wallet::class);
        $spectedWallet->expects('getCoinById')
            ->with($coinId)
            ->andReturn(new Coin($coinId, '', '', 4));

        $this->walletDataSource
            ->expects('searchWallet')
            ->with($walletId)
            ->andReturn($spectedWallet);

        $this->walletDataSource
            ->expects('saveWallet')
            ->with($spectedWallet)
            ->andReturnNull();

        $response = $this->postJson('/api/coin/sell', [
            'coin_id' => $coinId,
            'wallet_id' => $walletId,
            'amount_usd' => 2]);

        $response->assertStatus(200);
        $response->assertExactJson(['venta realizada']);
    }

    /**
     * @test
     */
    public function returnsErrorOnCoinNotEnoughAmmount()
    {
        $walletId = 'walletId';
        $coinId = '1';

        $spectedWallet = Mockery::mock(Wallet::class);
        $spectedWallet->expects('getCoinById')
            ->with($coinId)
            ->andReturn(new Coin($coinId, '', '', 2));

        $this->walletDataSource
            ->expects('searchWallet')
            ->with($walletId)
            ->andReturn($spectedWallet);

        $response = $this->postJson('/api/coin/sell', [
            'coin_id' => $coinId,
            'wallet_id' => $walletId,
            'amount_usd' => 4]);

        $response->assertStatus(400);
        $response->assertExactJson([]);
    }
}
