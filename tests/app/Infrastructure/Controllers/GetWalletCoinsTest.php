<?php

namespace Tests\app\Infrastructure\Controllers;

use App\Application\GetWalletCoinsService;
use App\Domain\Coin;
use App\Domain\Wallet;
use App\Infrastructure\Persistence\WalletDataSource;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class GetWalletCoinsTest extends TestCase
{
    private WalletDataSource $walletDataSource;
    private GetWalletCoinsService $getWalletCoinsService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->getWalletCoinsService = new GetWalletCoinsService($this->walletDataSource);
        $this->app->bind(GetWalletCoinsService::class, function () {
            return $this->getWalletCoinsService;
        });
    }

    /**
     * @test
     */
    public function walletDoesNotExists()
    {
        $this->walletDataSource
            ->expects('searchWallet')
            ->with('1_1')
            ->andReturn(null);
        $response = $this->get('/api/wallet/1_1/');

        $response->assertStatus(404);
        $response->assertExactJson([]);
    }

    /**
     * @test
     */
    public function getWalletCoinsBadRequest()
    {

        $response = $this->get('/api/wallet/&23/');

        $response->assertStatus(400);
        $response->assertExactJson([]);
    }

    /**
     * @test
     */
    public function getWalletCoinsRegularUse()
    {
        $wallet = new Wallet('1_1');
        $testCoin1 = new Coin('10', 'testcoin1', 'tc1', 1, 1);
        $wallet->buy($testCoin1);

        $this->walletDataSource
            ->expects('searchWallet')
            ->with('1_1')
            ->andReturn($wallet);

        $response = $this->get('/api/wallet/1_1/');

        $response->assertStatus(200);
        $response->assertExactJson([["amount" => 1,
            "coin_id" => "10",
            "name" => "testcoin1",
            "symbol" => "tc1",
            "value_usd" => 1]]);
    }
}
