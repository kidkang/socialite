<?php
namespace Yjtec\Socialite;

use Illuminate\Support\Arr;
use Illuminate\Support\Manager;
use Illuminate\Support\Str;
use \InvalidArgumentException;
use Yjtec\Socialite\Two\GithubProvider;
use Yjtec\Socialite\Two\WechatProvider;
use Yjtec\Socialite\Two\WechatAppProvider;
use Yjtec\Socialite\Two\GitlabProvider;
use Yjtec\Socialite\Two\AppleProvider;
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

    public function createWechatDriver(){
        $config = $this->app['config']['services.wechat'];
        return $this->buildProvider(
            WechatProvider::class,$config
        );
    }

    public function createGitlabDriver(){
        $config = $this->app['config']['services.gitlab'];
        return $this->buildProvider(
            GitlabProvider::class,$config
        );
    }

    public function createWechatAppDriver(){
        $config = $this->app['config']['services.wechat_app'];
        return $this->buildProvider(
            WechatAppProvider::class,$config
        );
    }

    public function createAppleDriver(){
        $config = $this->app['config']['services.apple'];
        return $this->buildProvider(
            AppleProvider::class,$config
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
