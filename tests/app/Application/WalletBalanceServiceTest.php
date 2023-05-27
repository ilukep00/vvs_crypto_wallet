<?php

namespace Tests\Application;

use App\Application\WalletBalanceService;
use App\Domain\Coin;
use App\Domain\Wallet;
use App\Infrastructure\Persistence\CoinDataSource;
use App\Infrastructure\Persistence\WalletDataSource;
use Tests\TestCase;
use Mockery;

class WalletBalanceServiceTest extends TestCase
{
    /**
     * @test
     */
    public function returnExceptionIfWalletNotFound()
    {
        $coinDataSource = Mockery::mock(CoinDataSource::class);
        $walletDataSource = Mockery::mock(WalletDataSource::class);
        $walletDataSource->expects("searchWallet")
            ->with("1")
            ->andReturnNull();

        $walletBalanceService = new WalletBalanceService($coinDataSource, $walletDataSource);
        $wallet_id = "1";

        $this->expectExceptionMessage("Cartera no encontrada");

        $walletBalanceService->execute($wallet_id);
    }

    /**
     * @test
     */
    public function returnWalletBalance()
    {
        $expectedWallet = new Wallet('1_1');
        $coin1 = new Coin('1', '', '', 10, 20);
        $coin2 = new Coin('2', '', '', 5, 15);
        $expectedWallet->buy($coin1);
        $expectedWallet->buy($coin2);

        $coinDataSource = Mockery::mock(CoinDataSource::class);
        $walletDataSource = Mockery::mock(WalletDataSource::class);
        $walletDataSource->expects("searchWallet")
            ->with("1_1")
            ->andReturn($expectedWallet);
        $coinDataSource->expects('getCoinPrize')
            ->with('1')
            ->andReturn(1.0);
        $coinDataSource->expects('getCoinPrize')
            ->with('2')
            ->andReturn(1.0);

        $walletBalanceService = new WalletBalanceService($coinDataSource, $walletDataSource);

        $response = $walletBalanceService->execute("1_1");

        $this->assertEquals($response, -20);
    }
}
