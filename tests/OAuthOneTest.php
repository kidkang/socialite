<?php

namespace Yjtec\Socialite\Tests;

use PHPUnit\Framework\TestCase;
use Mockery as m;


class OAuthOneTest extends TestCase
{

    public function tearDown(): void
    {
        parent::tearDown();
        m::close();
    }
    public function testFirst()
    {
        $this->assertTrue(true);
    }
}
