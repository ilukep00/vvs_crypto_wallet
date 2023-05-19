<?php

namespace Tests\app\Infrastructure\Persistence;

use App\Domain\Wallet;
use App\Infrastructure\Persistence\WalletDataSource;
use Illuminate\Support\Facades\Cache;
use App\Domain\User;
use Tests\TestCase;

class WalletDataSourceTest extends TestCase
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
    public function createsWalletWhenUserExists()
    {
        $user = new User(1);
        Cache::shouldReceive('get')
            ->with('user_1')
            ->andReturn($user);
        Cache::shouldReceive('forever')
            ->atLeast(1);

        $response = $this->walletDataSource->createWallet('1');

        $this->assertEquals('1_1', $response);
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
    public function returnsNullWhenUserDontExist()
    {
        Cache::shouldReceive('get')
            ->with('user_1')
            ->andReturn(null);

        $response = $this->walletDataSource->createWallet('1');

        $this->assertNull($response);
    }
}
