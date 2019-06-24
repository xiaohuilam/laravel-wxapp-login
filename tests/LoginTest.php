<?php
namespace XiaohuiLam\Laravel\WechatAppLogin\Test;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use XiaohuiLam\Laravel\WechatAppLogin\Test\AbstractTest\AbstractTest;
use XiaohuiLam\Laravel\WechatAppLogin\Facade;
use Illuminate\Support\Str;

class LoginTest extends AbstractTest
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLogin()
    {
        $code = Str::random();

        Facade::shouldReceive('login')
            ->with($code)
            ->once()
            ->andReturn([
                'openid' => 111,
            ]);

        $response = $this->post('/api/login', ['code' => $code]);

        dump($response->getOriginalContent());
        $response->assertStatus(200);
    }
}
