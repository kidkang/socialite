<?php

namespace Yjtec\Socialite\Tests;

use Yjtec\Socialite\AbstractUser;
use PHPUnit\Framework\TestCase;
use Mockery as m;



class AbstractUserTest extends TestCase
{
    public function testAbstract(){
        $user = $this->getUser();
        $user['name'] = 'kidkang';
        $this->assertSame($user['name'],'kidkang');
    }

    public function getUser(){
        $user = new User;
    }
}



class User extends AbstractUser{
    public function __construct(){
        
    }
}


