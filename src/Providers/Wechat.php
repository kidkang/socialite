<?php
namespace Yjtec\Socialite\Providers;

use Illuminate\Support\Arr;
use Yjtec\Socialite\Contracts\Provider as ProviderContract;

class Wechat extends Provider implements ProviderContract
{
    const NAME                 = 'wechat';
    protected $withCountryCode = false;
    protected $scopes          = ['snsapi_login'];
    protected $openid;
    protected $baseUrl = 'https://api.weixin.qq.com/sns';

    public function user()
    {
        if ($this->hasInvalidState()) {
            throw new \InvalidArgumentException('state is used!');
        }

        $response = $this->getAccessTokenResponse($this->getCode());
        $this->checkResponse($response);
        $this->withOpenid($response['openid']);

        $user = $this->mapUserToObject($this->getUserByToken(
            $token = Arr::get($response, 'access_token')
        ));

        return $user->setToken($token)
            ->setRefreshToken(Arr::get($response, 'refresh_token'))
            ->setExpiresIn(Arr::get($response, 'expires_in'));
    }

    protected function checkResponse($response)
    {
        if (isset($response['errcode']) && $response['errcode'] != 0) {
            throw new \InvalidArgumentException('You get error: ' . json_encode($response, JSON_UNESCAPED_UNICODE));
        }
    }

    public function withOpenid(string $openid): self
    {
        $this->openid = $openid;

        return $this;
    }

    public function withCountryCode()
    {
        $this->withCountryCode = true;

        return $this;
    }
    /**
     * Get the authentication URL for the provider.
     *
     * @param  string  $state
     * @return string
     */
    protected function getAuthUrl($state)
    {
        $path = 'oauth2/authorize';
        if ($this->request->isWechat()) {
            $this->setScopes('snsapi_userinfo');
            return $this->buildAuthUrlFromBase("https://open.weixin.qq.com/connect/{$path}", $state);
        }
        if (in_array('snsapi_login', $this->scopes)) {
            $path = 'qrconnect';
        }
        return $this->buildAuthUrlFromBase("https://open.weixin.qq.com/connect/{$path}", $state);
    }

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        return 'https://api.weixin.qq.com/sns/oauth2/access_token';
    }

    /**
     * Get the raw user for the given access token.
     *
     * @param  string  $token
     * @return array
     */
    protected function getUserByToken($token)
    {
        $language = $this->withCountryCode ? null : (isset($this->parameters['lang']) ? $this->parameters['lang'] : 'zh_CN');
        $response = $this->getHttpClient()->get($this->baseUrl . '/userinfo', [
            'query' => array_filter([
                'access_token' => $token,
                'openid'       => $this->openid,
                'lang'         => $language,
            ]),
        ]);

        return \json_decode($response->getBody(), true) ?? [];
    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param  array  $user
     * @return \Laravel\Socialite\Two\User
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id'       => isset($user['unionid']) ? $user['unionid'] : $user['openid'],
            'nickname' => $user['nickname'],
            'name'     => $user['nickname'],
            'avatar'   => $user['headimgurl'],
        ]);
    }
    protected function getTokenFields($code)
    {

        return [
            'appid'      => $this->clientId,
            'secret'     => $this->clientSecret,
            'code'       => $code,
            'grant_type' => 'authorization_code',
        ];
    }

    protected function getCodeFields($state = null)
    {
        $fields = parent::getCodeFields($state);
        unset($fields['client_id']);
        return ['appid' => $this->clientId] + $fields;
    }
}
