<?php

namespace Yjtec\Socialite\Two;

use Illuminate\Support\Arr;

class WechatProvider extends AbstractProvider implements ProviderInterface
{

    /**
     * {@inheritdoc}
     */
    public function user()
    {
        if ($this->hasInvalidState()) {
            throw new InvalidStateException;
        }

        $response = $this->getAccessTokenResponse($this->getCode());

        $user = $this->mapUserToObject($this->getUser(
            $token = Arr::get($response, 'access_token'),
            $openid = Arr::get($response, 'openid')
        ));

        return $user->setToken($token)
            ->setRefreshToken(Arr::get($response, 'refresh_token'))
            ->setExpiresIn(Arr::get($response, 'expires_in'));
    }
    public function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://open.weixin.qq.com/connect/oauth2/authorize', $state);
    }

    public function getTokenUrl()
    {
        return $this->buildTokenUrlFromBase('https://api.weixin.qq.com/sns/oauth2/access_token');
    }
    public function getUserByToken($token)
    {

    }
    public function getUser($token, $openid)
    {
        $userUrl = 'https://api.weixin.qq.com/sns/userinfo';
        $fields  = [
            'access_token' => $token,
            'openid'       => $openid,
            'lang'         => 'zh_CN',
        ];
        $url      = $userUrl . '?' . http_build_query($fields, '', '&', $this->encodingType);
        $response = $this->getHttpClient()->get($url);
        $user     = json_decode($response->getBody(), true);
        return $user;
    }

    public function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id'       => $user['openid'],
            'nickname' => $user['nickname'],
            'avatar'   => $user['headimgurl'],
        ]);
    }

    protected function getCodeFields($state = null)
    {
        $this->setScopes('snsapi_userinfo');
        $fields = parent::getCodeFields($state);
        unset($fields['client_id']);
        $fields['appid'] = $this->clientId;
        return $fields;
    }

    protected function buildTokenUrlFromBase($url)
    {
        $fields = [
            'appid'      => $this->clientId,
            'secret'     => $this->clientSecret,
            'code'       => $this->getCode(),
            'grant_type' => 'authorization_code',
        ];
        return $url . '?' . http_build_query($fields, '', '&', $this->encodingType);
    }
}
