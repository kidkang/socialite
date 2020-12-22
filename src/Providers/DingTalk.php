<?php
namespace Yjtec\Socialite\Providers;
use Illuminate\Support\Arr;
use Yjtec\Socialite\Contracts\Provider as ProviderContract;
class DingTalk extends Provider implements ProviderContract{
    public const NAME = 'dingtalk';
    protected $scopes = ['snsapi_auth'];

    protected $stateless = true;
    /**
     * Get the authentication URL for the provider.
     *
     * @param  string  $state
     * @return string
     */
    protected function getAuthUrl($state){
        dd($this->request->isDingtalk());
        //dd($this->request->header());
        return $this->buildAuthUrlFromBase('https://oapi.dingtalk.com/connect/oauth2/sns_authorize',$state);
    }

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl(){
        return 'https://oapi.dingtalk.com/gettoken';
    }

    public function getAccessTokenResponse($code){

        $response = $this->getHttpClient()->get(
            $this->getTokenUrl().'?'.http_build_query($this->getTokenFields($code))
        );
        $response = json_decode($response->getBody()->getContents(), true);
        $this->checkResponse($response);
        return $response;
        
    }

    protected function getTokenFields($code){
        return [
            'appkey' => $this->clientId,
            'appsecret' => $this->clientSecret
        ];
    }


    protected function checkResponse($response){
        if (0 != $response['errcode'] ?? 1) {
            throw new \InvalidArgumentException('You get error: ' . json_encode($response, JSON_UNESCAPED_UNICODE));
        }
    }

    protected function getCodeUrl(){
        return 'https://oapi.dingtalk.com/sns/getuserinfo_bycode';
    }

    public function userFromCode(): User
    {
        $code = $this->getCode();
        $time = (int)microtime(true) * 1000;
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
        dd($response);
        $this->checkResponse($response);

        $user = $response['user_info'];

        return (new User)->setRaw($user)->map([
            'id' => Arr::get($user,'dingId'),
            'nickname' => Arr::get($user,'nick'),
            'openid' => Arr::get($user, 'openid'),
            'unionid' => Arr::get($user, 'unionid')
        ]);
    }

    protected function createSignature(int $time)
    {
        return base64_encode(hash_hmac('sha256', $time, $this->clientSecret, true));
    }
    /**
     * Get the raw user for the given access token.
     *
     * @param  string  $token
     * @return array
     */
    protected function getUserByToken($token){
        dd($token);
    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param  array  $user
     * @return \Laravel\Socialite\Two\User
     */
    protected function mapUserToObject(array $user){
        dd($user);
    }

    

    protected function getCodeFields($state = null){
        $fields = parent::getCodeFields($state);
        unset($fields['client_id']);
        return  ['appid' => $this->clientId] + $fields;
    }
}