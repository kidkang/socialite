<?php
namespace Yjtec\Socialite\Three;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ThreeProvider
{
    protected $instances = [];

    public function __construct(Request $request, $config, $driver)
    {
        $this->request = $request;
        $this->config  = $config;
        $this->driver  = $driver;

        return $this;
    }

    public function getInstance($instance = null)
    {
        if ($instance) {
            if (array_key_exists($instance, $this->config)) {
                return $this->buildProvider($this->config[$instance],$instance);
            }

            throw new InvalidArgumentException('no instance');
        }

        return $this->buildProvider($this->config);
    }

    public function buildProvider($config,$instance = null)
    {
        $provider = $this->driver;
        return new $provider(
            $this->request, $config['client_id'],
            $config['client_secret'], $this->formatRedirectUrl($config),
            Arr::get($config, 'guzzle', []),
            $instance
        );
    }

    public function __call($method, $args)
    {
        return $this->getInstance()->$method(...$args);
    }

    public function formatRedirectUrl(array $config)
    {
        $redirect = value($config['redirect']);

        return Str::startsWith($redirect, '/')
        ? url()->to($redirect)
        : $redirect;
    }
}
