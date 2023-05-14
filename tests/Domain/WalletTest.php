<?php

namespace Tests\Domain;

use App\Domain\Coin;
use App\Domain\Wallet;
use PHPUnit\Framework\TestCase;

class WalletTest extends TestCase
{
    /**
     * @test
     */
    public function appendsCoinWhenBuyNew()
    {
        $wallet = new Wallet(1);

        $wallet->buy(new Coin('1'));
        $wallet->buy(new Coin('2'));
        $wallet->buy(new Coin('3'));

        $this->assertEquals(3, $wallet->getNumOfBuyedCoins());
    }

    /**
     * @test
     */
    public function sumsCoinWhenBuyOld()
    {
        $wallet = new Wallet(1);

        $coin1 = new Coin(
            1,
            'moneda1',
            '.',
            5,
            100
        );
        $wallet->buy(new Coin(2));
        $wallet->buy($coin1);
        $wallet->buy(new Coin(3));
        $wallet->buy($coin1);

        $this->assertEquals(10, $wallet->getCoinById(1)->ammount);
    }
}
