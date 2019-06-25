<?php
namespace XiaohuiLam\Laravel\WechatAppLogin\Test;

use App\User;
use Illuminate\Support\Str;
use XiaohuiLam\Laravel\WechatAppLogin\Facade;
use XiaohuiLam\Laravel\WechatAppLogin\Test\AbstractTest\AbstractTest;
use Illuminate\Support\Facades\Auth;

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
        $openid = Str::random();
        $user = User::create([
            'name' => $openid,
            'openid' => $openid,
            'email' => $openid . '@wechat.com',
            'password' => $openid,
        ]);

        Facade::shouldReceive('login')
            ->with($code)
            ->once()
            ->andReturn([
                'openid' => $openid,
            ]);

        $this->post('/api/login', ['code' => $code])
            ->see('token');
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLoginFail()
    {
        $code = Str::random();

        Facade::shouldReceive('login')
            ->with($code)
            ->once()
            ->andReturn([
                'code' => -1,
            ]);

        $response = $this->post('/api/login', ['code' => $code]);

        //$this->assertEquals(403, $response->getStatusCode());
        //$this->assertTrue(Str::contains($response->getContent(), 'bad code'));
    }
}
