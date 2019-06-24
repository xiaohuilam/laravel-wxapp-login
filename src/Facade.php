<?php
namespace XiaohuiLam\Laravel\WechatAppLogin;

use Illuminate\Support\Facades\Facade as IlluminateFacade;

/**
 * Class Facade.
 *
 * @author overtrue <i@overtrue.me>
 */
class Facade extends IlluminateFacade
{
    /**
     * 默认为小程序.
     *
     * @return string
     */
    public static function getFacadeAccessor()
    {
        return 'wechat.login';
    }
}
