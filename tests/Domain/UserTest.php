<?php

namespace Tests\Domain;

use App\Domain\User;
use App\Domain\Wallet;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function canCreateMultipleWallets()
    {
        $user = new User(1);

        $walletNum = 3;
        for ($i = 0; $i < $walletNum; $i++) {
            $user->newWallet();
        }

        $this->assertEquals($walletNum, $user->getNumOfWallets());
    }
}
