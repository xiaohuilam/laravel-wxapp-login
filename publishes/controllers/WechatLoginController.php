<?php
namespace App\Http\Controllers\Auth;

use XiaohuiLam\Laravel\WechatAppLogin\Http\Controllers\Auth\WechatLoginController as BaseWechatLoginController;
use Illuminate\Support\Str;

class WechatLoginController extends BaseWechatLoginController
{
    /**
     * 注册用户时的attribtues
     *
     * @param array $credential
     * @return array
     */
    protected function userAttributes($credential)
    {
        $attributes = $credential;

        $name = Str::random(8);
        $attributes['name'] = $name;
        $attributes['email'] = $name . '@wechat.com';

        return $attributes;
    }
}
