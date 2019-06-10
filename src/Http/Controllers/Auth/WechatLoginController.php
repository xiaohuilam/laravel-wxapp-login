<?php

namespace XiaohuiLam\Laravel\WechatAppLogin\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class WechatLoginController extends Controller
{
    /**
     * 使用openid登录
     *
     * @return void
     */
    public function login()
    {
        $code = request()->input('code');
        if (!$code) {
            abort(403, 'bad param');
        }

        /**
         * @var \EasyWeChat\MiniProgram\Application $wechat
         */
        $wechat = app('wechat.mini_program');
        $response = $wechat->auth->session($code);
        $openid = $response['openid'];
        if (!$openid) {
            abort(403, 'bad code');
        }

        $credential = ['openid' => $openid];
        $user = null;

        if (!auth()->guard('wechat')->attempt($credential) || !$user = auth()->guard('wechat')->user()) {
            $user = $this->registerUser($credential);
        }

        $this->afterLogin($user, $credential);
        return response()->success(['token' => encrypt($openid),]);
    }

    /**
     * （当openid查找不到用户时）注册用户
     *
     * @param array $credential
     * @return void
     */
    protected function registerUser($credential)
    {
        $user_class = config('auth.providers.users.model');
        /**
         * @var \Illuminate\Foundation\Auth\User $user
         */
        $user = new $user_class($this->userAttributes($credential));
        $user->save();

        $this->afterRegister($user, $credential);
        return $user;
    }

    /**
     * 注册用户时的attribtues
     *
     * @param array $credential
     * @return array
     */
    protected function userAttributes($credential)
    {
        return $credential;
    }

    /**
     * 登陆后触发
     *
     * @param \Illuminate\Foundation\Auth\User $user
     * @param array $credential
     * @return void
     */
    protected function afterLogin($user, $credential)
    {
    }

    /**
     * 注册后触发
     *
     * @param \Illuminate\Foundation\Auth\User $user
     * @param array $credential
     * @return void
     */
    protected function afterRegister($user, $credential)
    {
    }
}
