<?php
namespace XiaohuiLam\Laravel\WechatAppLogin\Test\AbstractTest;

use Tests\TestCase;
use TestCase as LaravelTestCase;

if (!class_exists(TestCase::class)) {
    abstract class InterTestCase extends LaravelTestCase
    {
    }
} else {
    abstract class InterTestCase extends TestCase
    {
    }
}
