<?php
namespace XiaohuiLam\Laravel\WechatAppLogin\Test\AbstractTest;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Console\Kernel;
use Xiaohuilam\LaravelResponseSuccess\ResponseServiceProvider;
use XiaohuiLam\Laravel\WechatAppLogin\Traits\ControllerNamespaces;
use XiaohuiLam\Laravel\WechatAppLogin\WechatAppLoginServiceProvider;
use Overtrue\LaravelWeChat\ServiceProvider as EasywechatServiceProvider;
use XiaohuiLam\Laravel\WechatAppLogin\Test\Traits\AssertTrait;
use Illuminate\Http\JsonResponse;

/**
 * @method \Illuminate\Foundation\Testing\TestCase|\Illuminate\Http\Response|\Illuminate\Foundation\Testing\TestResponse post()
 */
abstract class AbstractTest extends InterTestCase
{
    use ControllerNamespaces, AssertTrait;

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
        config()->set('auth.providers.users.model', \App\Models\User::class);

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
        $this->registerMacros();
        $this->migrateTables();

        return $app;
    }

    protected function registerRoutes()
    {
        Route::group([
            'prefix' => 'api',
            'namespace' => $this->namespace,
        ], function () {
            require __DIR__ . '/../../publishes/routes/wechat.php';
        });
    }

    protected function registerMacros()
    {
        if (!JsonResponse::class instanceof Macroable)
        {
            return;
        }
        JsonResponse::macro('see', function ($string) {
            if (method_exists($this, 'assertStringContainsString')) {
                return $this->assertStringContainsString($string, $this->getContent());
            } else if (method_exists($this, 'assertContains')) {
                return $this->assertContains($string, $this->getContent());
            } else if (method_exists($this, 'see')) {
                return $this->see($string);
            }
        });
        JsonResponse::macro('dontSee', function ($string) {
            if (method_exists($this, 'assertStringNotContainsString')) {
                return $this->assertStringNotContainsString($string, $this->getContent());
            } else if (method_exists($this, 'assertNotContains')) {
                return $this->assertNotContains($string, $this->getContent());
            } else if (method_exists($this, 'dontSee')) {
                return $this->dontSee($string);
            }
        });
    }

    protected function migrateTables()
    {
        copy(__DIR__ . '/../../publishes/migrations/2019_05_28_060312_users_add_openid.php', __DIR__ . '/../../vendor/laravel/laravel/database/migrations/2019_05_28_060312_users_add_openid.php');
        Artisan::call('migrate');
    }
}
