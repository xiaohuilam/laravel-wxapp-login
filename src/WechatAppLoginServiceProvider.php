<?php

namespace XiaohuiLam\Laravel\WechatAppLogin;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Foundation\Application;
use App\Auth\WechatGuard;

class WechatAppLoginServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->mapWechatRoutes();

        $this->extendAuthGuardConfig();

        $this->extendAuthManager();
    }

    public function register()
    {
        $this->publishFiles();
    }

    protected function publishFiles()
    {
        $this->publishes([
            dirname(__DIR__) . '/publishes/guard/WechatGuard.php' => app_path('Auth/WechatGuard.php'),
            dirname(__DIR__) . '/publishes/controllers/WechatLoginController.php' => app_path('Http/Controllers/Auth/WechatLoginController.php'),
            dirname(__DIR__) . '/publishes/routes/wechat.php' => base_path('routes/wechat.php'),
        ], 'wechat-login');
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapWechatRoutes()
    {
        if (!class_exists(WechatGuard::class)) {
            return;
        }

        Route::prefix('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/wechat.php'));
    }

    /**
     * 扩充guard
     *
     * @return void
     */
    protected function extendAuthGuardConfig()
    {
        config()->set('auth.guards.wechat',[
            'driver' => 'wechat',
            'provider' => 'users',
            'input_key' => 'token',
            'storage_key' => 'openid',
            'hash' => false,
        ]);
    }

    /**
     * 注册auth
     *
     * @return void
     */
    protected function extendAuthManager()
    {
        auth()->extend('wechat', function (Application $app, $name, $config) {
            // The token guard implements a basic API token based guard implementation
            // that takes an API token field from the request and matches it to the
            // user in the database or another persistence layer where users are.
            $guard = new WechatGuard(
                auth()->createUserProvider($config['provider'] ?? null),
                $this->app['request'],
                $config['input_key'] ?? 'api_token',
                $config['storage_key'] ?? 'api_token',
                $config['hash'] ?? false
            );

            $this->app->refresh('request', $guard, 'setRequest');

            return $guard;
        });
    }
}
