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

## 使用

### 接口使用

在 `routes/wechat.php` 下面这个组中间，放置你的需要登录的 api 路由
```php
Route::group(['middleware' => ['auth:wechat']], function () {
    // The routes need login
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