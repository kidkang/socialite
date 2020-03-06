<?php
namespace Yjtec\Socialite;

use Illuminate\Support\Arr;
use Illuminate\Support\Manager;
use Illuminate\Support\Str;
use \InvalidArgumentException;
use Yjtec\Socialite\Two\GithubProvider;
class SocialiteManager extends Manager implements Contracts\Factory
{
    /**
     * Get a driver instance.
     * @param  string $driver
     * @return mixed
     */
    public function with($driver)
    {
        return $this->driver($driver);
    }

    public function createGithubDriver(){
        $config = $this->app['config']['services.github'];
        return $this->buildProvider(
            GithubProvider::class,$config
        );
    }

    /**
     * Build an OAuth 2 provider instance.
     *
     * @param  string  $provider
     * @param  array  $config
     * @return \Laravel\Socialite\Two\AbstractProvider
     */
    public function buildProvider($provider, $config)
    {
        return new $provider(
            $this->app['request'], $config['client_id'],
            $config['client_secret'], $this->formatRedirectUrl($config),
            Arr::get($config, 'guzzle', [])
        );
    }

    /**
     * Format the callback URL, resolving a relative URI if needed.
     *
     * @param  array  $config
     * @return string
     */
    protected function formatRedirectUrl(array $config)
    {
        $redirect = value($config['redirect']);

        return Str::startsWith($redirect, '/')
                    ? $this->app['url']->to($redirect)
                    : $redirect;
    }

    public function getDefaultDriver(){
        throw new InvalidArgumentException('No socialite driver was specified.');
    }
}
