<?php

namespace Yjtec\Socialite;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;

class SocialiteServiceProvider extends ServiceProvider
{
    protected $providers = [
        Providers\DingTalk::NAME => Providers\DingTalk::class,
        Providers\Wechat::NAME   => Providers\Wechat::class,
    ];
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->extend(Factory::class, function ($manager, $app) {
            $this->registerProviders($manager);
            $this->registerCustermProvdier($manager);
            return $manager;
        });
    }

    protected function registerProviders($manager)
    {
        foreach ($this->providers as $provider => $class) {
            $config = $this->app['config']["services.{$provider}"];
            $manager->extend($provider, function () use ($config, $class) {
                return $this->app->make(Factory::class)->buildProvider($class, $config);
            });
        }
    }

    protected function registerCustermProvdier($manager)
    {
        $services = $this->app['config']["services"];
        collect($services)->filter(function ($item) {
            return isset($item['provider']) && isset($this->providers[$item['provider']]);
        })->map(function ($item, $provider) use ($manager) {
            $config = $item;
            $class  = $this->providers[$item['provider']];
            $manager->extend($provider, function () use ($config, $class) {
                return $this->app->make(Factory::class)->buildProvider($class, $config);
            });
        });
    }
}
