<?php

namespace Tests\Application;

use App\Application\SellCoinService;
use App\Domain\Coin;
use App\Domain\Wallet;
use App\Infrastructure\Persistence\WalletDataSource;
use Tests\TestCase;
use Mockery;

class SellCoinServiceTest extends TestCase
{
    private WalletDataSource $walletDataSource;
    private SellCoinService $sellCoinService;
    protected function setUp(): void
    {
        parent::setUp();
        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->sellCoinService = new SellCoinService($this->walletDataSource);
    }
    /**
     * @test
     */
    public function returnExceptionIfWalletNotFound()
    {
        $this->walletDataSource->expects("searchWallet")
            ->with("1")
            ->andReturnNull();

        $wallet_id = "1";
        $coin_id = "1";
        $amount_sd = 1;


        $this->expectExceptionMessage("Cartera no encontrada");

        $this->sellCoinService->execute($coin_id, $wallet_id, $amount_sd);
    }

    /**
     * @test
     */
    public function returnExceptionIfCoinNotFound()
    {
        $walletId = 'walletId';
        $coinId = '1';
        $amount_sd = 1;

        $wallet = Mockery::mock(Wallet::class);

        $this->walletDataSource
            ->expects('searchWallet')
            ->with($walletId)
            ->andReturn($wallet);

        $wallet->expects('getCoinById')
            ->with($coinId)
            ->andReturnNull();

        $this->expectExceptionMessage("Moneda no encontrada");

        $this->sellCoinService->execute($coinId, $walletId, $amount_sd);
    }
    /**
     * @test
     */
    public function returnsErrorOnCoinNotEnoughAmmount()
    {
        $walletId = 'walletId';
        $coinId = '1';
        $amount_sd = 4;

        $wallet = Mockery::mock(Wallet::class);
        $wallet->expects('getCoinById')
            ->with($coinId)
            ->andReturn(new Coin($coinId, '', '', 2));

        $this->walletDataSource
            ->expects('searchWallet')
            ->with($walletId)
            ->andReturn($wallet);

        $this->expectExceptionMessage("Cantidad incorrecta");

        $this->sellCoinService->execute($coinId, $walletId, $amount_sd);
    }
    /**
     * @test
     */
    public function coinSellCorrectly()
    {
        $walletId = 'walletId';
        $coinId = '1';
        $amount_sd = 4;

        $wallet = Mockery::mock(Wallet::class);
        $wallet->expects('getCoinById')
            ->with($coinId)
            ->andReturn(new Coin($coinId, '', '', 6));

        $this->walletDataSource
            ->expects('searchWallet')
            ->with($walletId)
            ->andReturn($wallet);

        $this->walletDataSource
            ->expects('saveWallet')
            ->with($wallet);

        $this->sellCoinService->execute($coinId, $walletId, $amount_sd);
    }
}
