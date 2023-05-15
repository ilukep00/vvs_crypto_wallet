<?php

namespace Tests\app\Infrastructure\Controller;

use App\Domain\Coin;
use App\Domain\Wallet;
use App\Infrastructure\Persistence\WalletDataSource;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class GetWalletCoinsTest extends TestCase
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
    public function walletDoesNotExists()
    {
        $this->walletDataSource
            ->expects('searchWallet')
            ->with('2')
            ->andReturn(null);
        $response = $this->get('/api/wallet/2/');

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
        $this->walletDataSource
            ->expects('searchWallet')
            ->with('111')
            ->andReturn(new Wallet('111'));
        /*
        $wallet = new Wallet(1);
        $testCoin1 = new Coin('10','testcoin1','tc1',1,1);
        $testCoin2 = new Coin('20','testcoin2','tc2',2,2);
        $wallet->buy($testCoin1);
        $wallet->buy($testCoin2);
        */

        $response = $this->get('/api/wallet/111/');

        $response->assertStatus(200);
        $response->assertExactJson([]);
    }
}
