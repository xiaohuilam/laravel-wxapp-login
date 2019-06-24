<?php
namespace XiaohuiLam\Laravel\WechatAppLogin;

class WechatAppLogin
{
    public function login($code)
    {
        /**
         * @var \EasyWeChat\MiniProgram\Application $wechat
         */
        $wechat = app('wechat.mini_program');
        return $wechat->auth->session($code);
    }
}
