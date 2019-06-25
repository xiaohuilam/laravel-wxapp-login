<?php
namespace XiaohuiLam\Laravel\WechatAppLogin\Test\AbstractTest;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Traits\Macroable;

class TestResponse extends JsonResponse
{
    use Macroable {
        Macroable::__call as macroCall;
    }
}