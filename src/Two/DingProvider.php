<?php

namespace Yjtec\Socialite\Two;
use GuzzleHttp\ClientInterface;
use Yjtec\Socialite\Exceptions\InvalidStateException;
class DingProvider extends AbstractProvider implements ProviderInterface
{

    public function user()
    {
        if ($this->hasInvalidState()) {
            throw new InvalidStateException;
        }

        $postKey = (version_compare(ClientInterface::VERSION, '6') === 1) ? 'form_params' : 'body';
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'headers' => [
                'Accept' => 'application/json',
            ],
            'json' => $this->getTokenFields($this->getCode())
        ]);

        $user = json_decode($response->getBody(), true);
        dd($user);
    }

    /**
     * Get the authentication URL for the provider.
     *
     * @param  string  $state
     * @return string
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://oapi.dingtalk.com/connect/oauth2/sns_authorize', $state);
    }

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        return $this->buildTokenUrlFromBase('https://oapi.dingtalk.com/sns/getuserinfo_bycode');
    }

    public function getUserByToken($token)
    {

    }

    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id'       => $user['id'],
            'nickname' => $user['username'],
            'name'     => $user['name'],
            'email'    => $user['email'],
            'avatar'   => $user['avatar_url'],
        ]);
    }

    public function getTokenFields($code)
    {
        return ['tmp_auth_code' => $code];
    }

    protected function getCodeFields($state = null)
    {
        $this->setScopes('snsapi_auth');
        $fields = parent::getCodeFields($state);
        unset($fields['client_id']);
        $fields['appid'] = $this->clientId;
        return $fields;
    }

    protected function buildTokenUrlFromBase($url)
    {
        $timestamp = (int)(microtime(true)*1000);
        return $url . '?accessKey='.$this->clientId.'&timestamp='.$timestamp.'&signature='.$this->buildSignature($timestamp, $this->clientSecret);
    }

    protected function buildSignature($timestamp, $appSecret)
    {
        $s         = hash_hmac('sha256', $timestamp, $appSecret, true);
        $signature = base64_encode($s);
        return urlencode($signature);
    }
}
