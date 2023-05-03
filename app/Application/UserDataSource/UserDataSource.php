<?php

namespace App\Application\UserDataSource;

use App\Domain\User;

interface UserDataSource
{
    public function findByEmail(string $email): User;

    /**
     * @return User[]
     */
    public function getAll(): array;
}
