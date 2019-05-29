<?php
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['guest']], function () {
    Route::post('login', 'Auth\\WechatLoginController@login')->name('wechat.login');
});

Route::group(['middleware' => ['auth:wechat']], function () {
    // The routes need login
});
