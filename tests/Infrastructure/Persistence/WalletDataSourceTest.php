<?php

namespace Tests\Infrastructure\Persistence;

use App\Infrastructure\Persistence\WalletDataSource;
use App\Domain\User;
use App\Domain\Wallet;
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
        $user = new User(1);
        Cache::shouldReceive('get')
            ->with('u_1')
            ->andReturn($user);
        Cache::expects('forever');

        $walletDataSource = new WalletDataSource();

        $response = $walletDataSource->createWallet('u_1');

        $this->assertEquals('1_1', $response);
    }

    /**
     * @test
     */
    public function returnsNullWhenUserDontExist()
    {
        Cache::shouldReceive('get')
            ->with('u_1')
            ->andReturn(null);

        $walletDataSource = new WalletDataSource();

        $response = $walletDataSource->createWallet('u_1');

        $this->assertNull($response);
    }
}