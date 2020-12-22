<?php
namespace Yjtec\Socialite\Providers;

use Illuminate\Support\Arr;
use Yjtec\Socialite\Contracts\Provider as ProviderContract;

class DingTalk extends Provider implements ProviderContract
{
    const NAME = 'dingtalk';

    public function user()
    {
        return $this->userFromCode();
    }
    /**
     * Get the authentication URL for the provider.
     *
     * @param  string  $state
     * @return string
     */
    protected function getAuthUrl($state)
    {
        if ($this->request->isDingtalk()) {
            return $this->getInnerAuthUrl($state);
        } else {
            if (in_array('pwd', $this->scopes)) {
                return $this->getPwdAuthUrl($state);
            }
            return $this->getCodeAuthUrl($state);
        }
    }

    protected function getInnerAuthUrl($state)
    {
        $this->setScopes('snsapi_auth');
        return $this->buildAuthUrlFromBase('https://oapi.dingtalk.com/connect/oauth2/sns_authorize', $state);
    }

    protected function getPwdAuthUrl($state)
    {
        $this->setScopes('snsapi_login');
        return $this->buildAuthUrlFromBase('https://oapi.dingtalk.com/connect/oauth2/sns_authorize', $state);
    }

    protected function getCodeAuthUrl($state)
    {
        $this->setScopes('snsapi_login');
        return $this->buildAuthUrlFromBase('https://oapi.dingtalk.com/connect/qrconnect', $state);
    }

    protected function getUserByToken($token): array
    {
        throw new \InvalidArgumentException('Unable to use token get User.');
    }
    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        throw new \InvalidArgumentException('not supported to get access token.');
    }

    protected function checkResponse($response)
    {
        if (0 != $response['errcode'] ?? 1) {
            throw new \InvalidArgumentException('You get error: ' . json_encode($response, JSON_UNESCAPED_UNICODE));
        }
    }

    protected function getCodeUrl()
    {
        return 'https://oapi.dingtalk.com/sns/getuserinfo_bycode';
    }

    public function userFromCode()
    {
        $code        = $this->getCode();
        $time        = (int) microtime(true) * 1000;
        $queryParams = [
            'accessKey' => $this->clientId,
            'timestamp' => $time,
            'signature' => $this->createSignature($time),
        ];
        $response = $this->getHttpClient()->post(
            $this->getCodeUrl() . '?' . http_build_query($queryParams),
            [
                'json' => ['tmp_auth_code' => $code],
            ]
        );
        $response = json_decode($response->getBody()->getContents(), true);

        $this->checkResponse($response);

        $user = $response['user_info'];

        return $this->mapUserToObject($user);
    }

    protected function createSignature(int $time)
    {
        return base64_encode(hash_hmac('sha256', $time, $this->clientSecret, true));
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
            'id'       => Arr::get($user, 'dingId'),
            'nickname' => Arr::get($user, 'nick'),
            'openid'   => Arr::get($user, 'openid'),
            'unionid'  => Arr::get($user, 'unionid'),
        ]);
    }

    protected function getCodeFields($state = null)
    {
        $fields = parent::getCodeFields($state);
        unset($fields['client_id']);
        return ['appid' => $this->clientId] + $fields;
    }
}
