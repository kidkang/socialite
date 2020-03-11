<?php

namespace Yjtec\Socialite\Tests\Two;

use PHPUnit\Framework\TestCase;
use Yjtec\Socialite\Two\AppleProvider;
use Illuminate\Support\Arr;

class GithubProviderTest extends TestCase
{
    public function testInstance()
    {

        $provider = new AppleProvider;
        $this->assertTrue($provider instanceof AppleProvider);
    }

    public function testDecodeToken()
    {
        $response = [
            "access_token"  => "a4522c53f49ea4f5d9ebb749a49c02339.0.nrvyu.aHFUMahu_POzf4Yy2jQUXA",
            "token_type"    => "Bearer",
            "expires_in"    => 3600,
            "refresh_token" => "rf7f6cad1a0a24fa79625e91a8529972a.0.nrvyu.NbM6f-JlUFGq6pAu_5rhFQ",
            "id_token"      => "eyJraWQiOiJlWGF1bm1MIiwiYWxnIjoiUlMyNTYifQ.eyJpc3MiOiJodHRwczovL2FwcGxlaWQuYXBwbGUuY29tIiwiYXVkIjoiY29tLmhueWprai5jb20iLCJleHAiOjE1ODM5MDU3NTksImlhdCI6MTU4MzkwNTE1OSwic3ViIjoiMDAxNTg0LjA2OTVmOWFhMDRjNDQ4N2ZhOTgwMmZmOTQ0NWMzMWY0LjEwMDIiLCJhdF9oYXNoIjoiQzNHRjd5anNrVmxXOVY3Y3Q2VXpKQSIsImF1dGhfdGltZSI6MTU4MzkwNTE1Nywibm9uY2Vfc3VwcG9ydGVkIjp0cnVlfQ.2VnedCmSF0wohZAOVOO-lk4l-0ZYMXSjY3EHNqLJNyUm95KKY1fy7a6eRer8Yyw_kPbh-mXFhm0qVfgEJOneIkNPZZ87bi-HBIqRfbBR7CzuIpYS0EGWm2hXxjDbd9Drqeamc1PREq_q3V8FGk8L-IhOMksxUYuoSyazV6LrAdIu4SiWAErnbcCVOnaB9T08ftkfQHqEShKifLnGUoHgH_ywx61L-B-m9bOqQH-hbneN958A2oI5Jq75IcPX6hrkbqQUCfBzHEiUDvOtaGBoqPVRmPW5KAZvZpH8V4DyEoYOlMuerewt1YdA1ke-2O1ozmoKXL__naktg7a3k-_tQA"
        ];
        
    }
}
