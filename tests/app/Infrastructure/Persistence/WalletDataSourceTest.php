<?php

namespace Tests\app\Infrastructure\Persistence;

use App\Domain\User;
use App\Infrastructure\Persistence\WalletDataSource;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class WalletDataSourceTest extends TestCase
{
    /**
     * @test
     */
    public function createsWalletWhenUserExists()
    {
        $user = new User(1);
        Cache::shouldReceive('get')
            ->with('user_1')
            ->andReturn($user);
        Cache::shouldReceive('forever')
            ->atLeast(1);

        $walletDataSource = new WalletDataSource();

        $response = $walletDataSource->createWallet('1');

        $this->assertEquals('1_1', $response);
    }

    /**
     * @test
     */
    public function returnsNullWhenUserDontExist()
    {
        Cache::shouldReceive('get')
            ->with('user_1')
            ->andReturn(null);

        $walletDataSource = new WalletDataSource();

        $response = $walletDataSource->createWallet('1');

        $this->assertNull($response);
    }
}
