<?php
namespace XiaohuiLam\Laravel\WechatAppLogin\Test;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;
use XiaohuiLam\Laravel\WechatAppLogin\Facade;
use XiaohuiLam\Laravel\WechatAppLogin\Test\AbstractTest\AbstractTest;

class LoginTest extends AbstractTest
{
    protected $guard = 'wechat';

    /**
     * 测试登陆
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

        $response = $this->post('/api/login', $this->buildParam(['code' => $code]));
        $response->assertSuccessful();
        if (method_exists($response, 'see')) {
            $response->see('token');
        } else {
            $this->assertArrayHasKey('token', $response->getOriginalContent()['data']);
        }
    }

    /**
     * 测试登陆失败
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

        $response = $this->post('/api/login', $this->buildParam(['code' => $code]));

        if (method_exists($response, 'see')) {
            $response->see('bad code');
        } else {
            $this->assertEquals('bad code', $response->getOriginalContent()['message']);
        }
    }

    /**
     * 测试登陆成功之后
     *
     * @return void
     */
    public function testLogined()
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
        $response->assertSuccessful();
        if (method_exists($response, 'see')) {
            $response->see('token');
            $json = $response->response->getContent();
            $data = json_decode($json);
        } else {
            $this->assertArrayHasKey('token', $response->getOriginalContent()['data']);
            $data = $response->getOriginalContent();
        }
        $token = data_get($data, 'data.token');

        auth()->guard($this->guard)->logout();

        $errmsg = 'can\'t retrive user';

        Route::get('/testing', function () use ($errmsg) {
            if ($user = auth()->guard($this->guard)->user()) {
                return response()->success($user);
            } else {
                return response()->error(400, $errmsg);
            }
        })->middleware('auth:' . $this->guard)->name('unit.logined');

        $response = $this->get('/testing', $this->buildParam(['Authorization' => 'Bearer ' . mt_rand(1, 99)]));
        if (method_exists($response, 'see')) {
            $response->see('Unauthenticated.');
        } else {
            $this->assertTrue(Str::contains($response->getContent(), 'Unauthenticated.'));
        }

        $response = $this->get('/testing', $this->buildParam([ 'Authorization' => 'Bearer ' . $token]));
        $response->assertSuccessful();
        if (method_exists($response, 'see')) {
            $response->see($openid);
        } else {
            $this->assertTrue(Str::contains($response->getContent(), $openid));
        }
    }
}
