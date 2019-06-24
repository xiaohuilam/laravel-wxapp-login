<?php
namespace XiaohuiLam\Laravel\WechatAppLogin\Test\AbstractTest;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Xiaohuilam\LaravelResponseSuccess\ResponseServiceProvider;
use XiaohuiLam\Laravel\WechatAppLogin\Traits\ControllerNamespaces;
use XiaohuiLam\Laravel\WechatAppLogin\WechatAppLoginServiceProvider;
use Overtrue\LaravelWeChat\ServiceProvider as EasywechatServiceProvider;

/**
 * @method \Illuminate\Foundation\Testing\TestResponse get($uri, $options)
 * @method \Illuminate\Foundation\Testing\TestResponse post($uri, $options)
 */
abstract class AbstractTest extends InterTestCase
{
    use ControllerNamespaces;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        /**
         * @var \Illuminate\Foundation\Application $app
         */
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
        $app->register(EasywechatServiceProvider::class);
        $app->register(ResponseServiceProvider::class);

        $this->registerRoutes();
        $this->migrateTables();

        return $app;
    }

    protected function registerRoutes()
    {
        Route::prefix('api')
            ->namespace($this->namespace)
            ->group(__DIR__ . '/../../publishes/routes/wechat.php');
    }

    protected function migrateTables()
    {
        copy(__DIR__ . '/../../publishes/migrations/2019_05_28_060312_users_add_openid.php', __DIR__ . '/../../vendor/laravel/laravel/database/migrations/2019_05_28_060312_users_add_openid.php');
        Artisan::call('migrate');
    }
}
