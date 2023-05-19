<?php

namespace Tests\app\Infrastructure\Persistance;

use App\Domain\Wallet;
use App\Infrastructure\Persistence\WalletDataSource;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class WalletDataSourceTest extends TestCase
{
    /**
     * @test
     */
    public function walletIsNotFoundInCache()
    {
        Cache::shouldReceive('get')
            ->with('wallet_0001')
            ->andReturnNull();

        $walletDataSource = new WalletDataSource();

        $response = $walletDataSource->searchWallet('0001');

        $this->assertNull($response);
    }

    /**
     * @test
     */
    public function walletIsFoundInCache()
    {
        $wallet = new Wallet('0001');
        Cache::shouldReceive('get')
            ->with('wallet_0001')
            ->andReturn($wallet);

        $walletDataSource = new WalletDataSource();

        $response = $walletDataSource->searchWallet('0001');

        $this->assertEquals($response, $wallet);
    }
}
