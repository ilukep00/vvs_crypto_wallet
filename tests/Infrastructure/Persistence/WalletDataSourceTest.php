<?php

namespace Tests\Infrastructure\Persistence;

use App\Infrastructure\Persistence\WalletDataSource;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;
use Mockery;

class WalletDataSourceTest extends TestCase
{
    /**
     * @test
     */
    public function createsWalletWhenUserExists()
    {
        Cache::shouldReceive('get')
            ->with('u_00001')
            ->andReturn('w_0000000001');

        $walletDataSource = new WalletDataSource();

        $response = $walletDataSource->createWallet('u_00001');

        $this->assertEquals('w_0000000001', $response);
    }

    /**
     * @test
     */
    public function returnsNullWhenUserDontExist()
    {
        Cache::shouldReceive('get')
            ->with('u_00001')
            ->andReturn(null);

        $walletDataSource = new WalletDataSource();

        $response = $walletDataSource->createWallet('u_00001');

        $this->assertNull($response);
    }
}
