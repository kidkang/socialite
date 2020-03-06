<?php

namespace Yjtec\Socialite\Facades;

use Illuminate\Support\Facades\Facade;
use Yjtec\Socialite\Contracts\Factory;

/**
 * @method static \Yjtec\Socialite\Contracts\Provider driver(string $driver = null)
 * @see \Yjtec\Socialite\SocialiteManager
 */
class Socialite extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }
}
