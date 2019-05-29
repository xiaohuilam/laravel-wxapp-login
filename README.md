# Laravel 快速接入微信小程序
---

## 安装
```bash
composer require xiaohuilam/laravel-wxapp-login -vvv
```

## 发布
执行以下命令发布路由和 controller
```bash
php artisan vendor:publish --tag=wechat-login
```

## 配置
```env
WECHAT_MINI_PROGRAM_APPID=#小程序的appid
WECHAT_MINI_PROGRAM_SECRET=#小程序的secret
```

## 使用

### 接口使用

在 `routes/wechat.php`
```php
Route::group(['middleware' => ['guest']], function () {
    Route::post('login', 'Auth\\WechatLoginController@login')->name('wechat.login');
    //这里放未登录的api
});

Route::group(['middleware' => ['auth:wechat']], function () {
    //这里放置你的需要登录的 api 路由，如用户资料API、修改资料API...
});
```

### 修改注册用户逻辑

在 `app/Http/Controllers/Auth/WechatLoginController.php` 中修改

方法为：
- `protected function registerUser($credential)` 完整的注册逻辑
- `protected function userAttributes($credential)` 用仅需修改用户属性时，只需覆盖此方法即可


### 在小程序JS使用

在
```bash
php artisan tinker
```
运行 `route('wechat.login')` 得到微信登录的 api 的 URL， 调用微信登录
```javascript
let url = '上面获得的url'
wx.login({
  success: (res) => {
      wx.request({
        url: url,
        method: 'POST',
        data: {
            code: res.code
        },
        headers: {
            Authorization: null
        },
        success: (response) => {
            // 这里拿到的token = response.data.token，给后面所有需要登录的api都带上 {headers: {Authorization: response.data.token}}
        },
      })
  },
})
```

## 授权
MIT