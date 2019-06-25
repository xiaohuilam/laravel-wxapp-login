<?php
namespace XiaohuiLam\Laravel\WechatAppLogin\Test\Traits;

trait AssertTrait
{
    public function assertSuccessful()
    {
        return $this->seeStatusCode(200);
    }

    public function assertFail()
    {
        return $this->seeStatusCode(403);
    }
}
