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