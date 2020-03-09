<?php

namespace Yjtec\Socialite\Two;

use Exception;
use Illuminate\Support\Arr;

class GitlabProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * Get the authentication URL for the provider.
     *
     * @param  string  $state
     * @return string
     */
    protected function getAuthUrl($state){
        return $this->buildAuthUrlFromBase('http://gitlab.hnyjkj.com/oauth/authorize',$state);
    }

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl(){
        return 'http://gitlab.hnyjkj.com/oauth/token';
    }
    
    /**
     * Get the raw user for the given access token.
     *
     * @param  string  $token
     * @return array
     */
    protected function getUserByToken($token){
        $userUrl = 'http://gitlab.hnyjkj.com/api/v3/user?access_token='.$token;

        $response = $this->getHttpClient()->get($userUrl);

        $user = json_decode($response->getBody(), true);

        return $user;
    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param  array  $user
     * @return \Laravel\Socialite\Two\User
     */
    protected function mapUserToObject(array $user){
        return (new User)->setRaw($user)->map([
            'id' => $user['id'],
            'nickname' => $user['username'],
            'name' => $user['name'],
            'email' => $user['email'],
            'avatar' => $user['avatar_url'],
        ]);
    }

    protected function getTokenFields($code){
        return parent::getTokenFields($code) + ['grant_type' => 'authorization_code'];
    }
}