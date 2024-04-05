<?php

namespace App\Interfaces;

interface AuthenticationRepositoryInterface
{
    public function storeUser(array $data);

    public function getToken(array $data);
}
