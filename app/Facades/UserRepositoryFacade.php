<?php

namespace App\Facades;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Facade;

class UserRepositoryFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return UserRepository::class;
    }
}
