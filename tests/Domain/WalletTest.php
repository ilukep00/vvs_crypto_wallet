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
}
