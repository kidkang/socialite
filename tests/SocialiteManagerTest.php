<?php

namespace Yjtec\Socialite\Tests;

use Yjtec\Socialite\SocialiteManager;
use PHPUnit\Framework\TestCase;
use Mockery as m;
use \InvalidArgumentException;

class AbstractUserTest extends TestCase
{
    protected $config = [
        'config' => [
            'services.gitlab' => [

            ]
        ]
    ];
    public function testHasNoSocialiteManagerDriver(){
        $app = m::mock(App::class);
        $manager = new SocialiteManager($app);
        $this->expectException(InvalidArgumentException::class);
        $manager->with('abc');
    }

    public function testHasSocialiteMangerDriver(){
        $manager = new SocialiteManager($this->config);
        $manager->with('githab');
    }
    


}


