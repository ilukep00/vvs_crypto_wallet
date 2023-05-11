<?php

namespace App\Domain;

class User
{
    private int $id;
    private int $numOfWallets;

    public function __construct(int $id)
    {
        $this->id = $id;
        $this->numOfWallets = 0;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNumOfWallets(): int
    {
        return $this->numOfWallets;
    }

    public function newWallet(): Wallet|null
    {
        $this->numOfWallets = $this->numOfWallets + 1;
        $walletId = sprintf('%010d%08d', $this->id, $this->getNumOfWallets());

        return new Wallet($walletId);
    }
}
