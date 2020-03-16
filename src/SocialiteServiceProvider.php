<?php

namespace Yjtec\Socialite;

use Illuminate\Support\ServiceProvider;
use Yjtec\Socialite\Contracts\Factory;
use Yjtec\Socialite\Commands\DatabaseCommand;

class SocialiteServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    public function boot(){
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        $this->publishes([
            __DIR__.'/database/factories' => database_path('/factories')
        ],'factory');
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
        $this->registerManager();
        
    }
    
    /**
     * Register manager
     * @return void
     */
    protected function registerManager(){
        $this->app->singleton(Factory::class, function ($app) {
            return new SocialiteManager($app);
        });
    }
    /**
     * register commands
     * @return void
     */
    protected function registerCommands(){
        $this->commands([
            DatabaseCommand::class
        ]);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [Factory::class];
    }
}