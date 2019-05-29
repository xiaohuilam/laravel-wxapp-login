<?php
namespace App\Http\Controllers\Auth;

use XiaohuiLam\Laravel\WechatAppLogin\Http\Controllers\Auth\WechatLoginController as BaseWechatLoginController;
use Illuminate\Support\Str;
use Carbon\Carbon;

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

        $attributes['email'] = Str::random(8) . '@wechat.com';
        $attributes['email_verified_at'] = Carbon::now();
        $attributes['password'] = Str::random(8);

        return $attributes;
    }
}
