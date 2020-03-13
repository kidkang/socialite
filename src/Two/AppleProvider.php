<?php

namespace Yjtec\Socialite\Two;

use Yjtec\AppleSignin\Facades\AppleSignin;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
class AppleProvider extends AbstractProvider implements ProviderInterface
{

    protected $appleSignin;


    public function __construct(Request $request, $clientId, $clientSecret, $redirectUrl, $guzzle = [],$instance)
    {
        
        $this->appleSignin = AppleSignin::getInstance($instance);
        $secret = $this->appleSignin->secret();
        parent::__construct($request,$clientId,$secret,$redirectUrl,$guzzle);
    }
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://appleid.apple.com/auth/authorize', $state);
    }

    protected function getTokenUrl()
    {
        return 'https://appleid.apple.com/auth/token';
    }

    public function user()
    {
        $response = $this->getAccessTokenResponse($this->getCode());
        $jwt = Arr::get($response,'id_token');
        $user = $this->mapUserToObject(
            (array) $this->appleSignin->decode($jwt)
        );
        return $user->setToken(Arr::get($response, 'access_token'))
                    ->setRefreshToken(Arr::get($response, 'refresh_token'))
                    ->setExpiresIn(Arr::get($response, 'expires_in'));
    }

    protected function getUserByToken($token)
    {

    }
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => $user['sub']
        ]);
    }

    protected function getCodeFields($state = null)
    {
        $fields = [
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->redirectUrl,
            'response_type' => 'code',
        ];

        if ($this->usesState()) {
            $fields['state'] = $state;
        }

        return array_merge($fields, $this->parameters);
    }

    protected function getTokenFields($code)
    {
        return parent::getTokenFields($code) + ['grant_type' => 'authorization_code'];
    }
}
