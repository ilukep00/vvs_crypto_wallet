<?php

namespace Tests\app\Infrastructure\Persistence;

use App\Domain\Wallet;
use App\Infrastructure\Persistence\WalletDataSource;
use Illuminate\Support\Facades\Cache;

class WalletDataSourceTest extends \Tests\TestCase
{
    private WalletDataSource $walletDataSource;
    protected function setUp(): void
    {
        parent::setUp();
        $this->walletDataSource = new WalletDataSource();
    }

    /**
     * @test
     */
    public function walletIsNotFoundInCache()
    {
        Cache::expects('get')
            ->with('wallet_3')
            ->andReturnNull();

        $response = $this->walletDataSource->searchWallet("3");

        $this->assertNull($response);
    }

    /**
     * @test
     */
    public function walletIsFoundInCache()
    {
        Cache::expects('get')
            ->with("wallet_5")
            ->andReturn(new Wallet("5"));

        $wallet = new Wallet("5");

        $response = $this->walletDataSource->searchWallet("5");

        $this->assertEquals($response, $wallet);
    }
    /**
     * @test
     */
    public function walletIsSave()
    {
        Cache::expects('forever');
        $wallet = new Wallet("4");

        $this->walletDataSource->saveWallet($wallet);
    }
}
