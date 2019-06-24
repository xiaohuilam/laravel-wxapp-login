<?php
namespace XiaohuiLam\Laravel\WechatAppLogin\Test;

use App\User;
use Tests\TestCase;
use Illuminate\Support\Str;
use XiaohuiLam\Laravel\WechatAppLogin\Facade;
use Illuminate\Foundation\Testing\RefreshDatabase;
use XiaohuiLam\Laravel\WechatAppLogin\Test\AbstractTest\AbstractTest;

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

        $response = $this->post('/api/login', ['code' => $code]);

        $response->assertStatus(200);
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

        $response->assertStatus(403);
        $response->assertSee('bad code');
    }
}
