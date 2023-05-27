<?php

namespace Tests\app\Infrastructure\Controllers;

use App\Application\WalletBalanceService;
use App\Domain\Coin;
use App\Domain\Wallet;
use App\Infrastructure\Persistence\CoinDataSource;
use App\Infrastructure\Persistence\WalletDataSource;
use Tests\TestCase;
use Mockery;

class GetWalletBalanceControllerTest extends TestCase
{
    private WalletDataSource $walletDataSource;
    private CoinDataSource $coinDataSource;
    private WalletBalanceService $walletBalanceService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->coinDataSource = Mockery::mock(CoinDataSource::class);
        $this->walletBalanceService = new WalletBalanceService($this->coinDataSource, $this->walletDataSource);

        $this->app->bind(WalletBalanceService::class, function () {
            return $this->walletBalanceService;
        });
    }

    /**
     * @test
     */
    public function returnsErrorOnBadRequest()
    {
        $response1 = $this->get('/api/wallet/1/balance');
        $response2 = $this->get('/api/wallet/1_a/balance');

        $response1->assertStatus(400);
        $response1->assertExactJson([]);
        $response2->assertStatus(400);
        $response2->assertExactJson([]);
    }

    /**
     * @test
     */
    public function returnsErrorOnWalletNotFound()
    {
        $this->walletDataSource->expects('searchWallet')
            ->with('1_1')
            ->andReturn(null);

        $response = $this->get('/api/wallet/1_1/balance');

        $response->assertStatus(404);
        $response->assertExactJson([]);
    }

    /**
     * @test
     */
    public function returnsWalletBalanceOnGoodRequest()
    {
        $expectedWallet = new Wallet('1_1');
        $coin1 = new Coin('1', '', '', 10, 20);
        $coin2 = new Coin('2', '', '', 5, 15);
        $expectedWallet->buy($coin1);
        $expectedWallet->buy($coin2);

        $this->walletDataSource->expects('searchWallet')
            ->with('1_1')
            ->andReturn($expectedWallet);

        $this->coinDataSource->expects('getCoinPrize')
            ->with('1')
            ->andReturn(1.0);
        $this->coinDataSource->expects('getCoinPrize')
            ->with('2')
            ->andReturn(1.0);

        $response = $this->get('/api/wallet/1_1/balance');

        $response->assertStatus(200);
        $response->assertExactJson(["balance_usd" => -20]);
    }
}
