<?php
namespace XiaohuiLam\Laravel\WechatAppLogin\Test\AbstractTest;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Console\Kernel;
use XiaohuiLam\Laravel\WechatAppLogin\WechatAppLoginServiceProvider;
use XiaohuiLam\Laravel\WechatAppLogin\Traits\ControllerNamespaces;
use Illuminate\Support\Facades\Route;
use Overtrue\LaravelWeChat\ServiceProvider;

/**
 * @method \Illuminate\Foundation\Testing\TestResponse get($uri, $options)
 * @method \Illuminate\Foundation\Testing\TestResponse post($uri, $options)
 */
abstract class AbstractTest extends TestCase
{
    use ControllerNamespaces;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../../vendor/laravel/laravel/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        config()->set('app.env', 'testing');
        config()->set('app.debug', true);
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite.database', ':memory:');
        config()->set('app.key', 'AckfSECXIvnK5r28GVIWUAxmbBSjTsmF');
        config()->set('wechat.mini_program.default', [
            'app_id'  => '1',
            'secret'  => '1',
            'token'   => '1',
            'aes_key' => '1',
        ]);

        $app->register(WechatAppLoginServiceProvider::class);
        $app->register(ServiceProvider::class);

        $this->registerRoutes();

        return $app;
    }

    protected function registerRoutes()
    {
        Route::prefix('api')
            ->namespace($this->namespace)
            ->group( __DIR__ . '/../../publishes/routes/wechat.php');
    }
}
