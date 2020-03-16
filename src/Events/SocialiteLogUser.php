<?php
namespace Yjtec\Socialite\Events;

use Illuminate\Queue\SerializesModels;
use Yjtec\Socialite\AbstractUser;

class SocialiteLogUser
{
    use SerializesModels;
    public $user;
    public $driver;
    public $clientId;
    public function __construct(AbstractUser $user, $driver, $clientId)
    {
        $this->user     = $user->user;
        $this->driver   = $driver;
        $this->clientId = $clientId;
        //dd($this->user);
    }
}
