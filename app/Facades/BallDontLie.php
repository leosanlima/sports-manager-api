<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class BallDontLie extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'balldontlie';
    }
}

