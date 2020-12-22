# socialite

社会化登陆包

## 配置

config/services.php添加如下配置

```
'github' => [
        'client_id' => 'appid',
        'client_secret' => 'secret',
        'redirect' => 'http://dev.ucenter.360vrsh.com/callback'
    ],
    'wechat' => [
        'client_id' => 'appid',
        'client_secret' => 'secret',
        'redirect' => url('/wechat/callback')
    ],
    'wechat_app' => [
        'client_id' => 'appid',
        'client_secret' => 'secret',
        'redirect' => ''
    ]
```

## 使用

### 钉钉登录
> web下默认跳转到钉钉扫码登录，如果是在钉钉应用内，直接默认授权登录

```
Route::get('dingtalk',function(){
    return Socialite::driver('dingtalk')->redirect();
});

Route::get('dingtalk/callback',function(){
    $user = Socialite::driver('dingtalk')->user();
    dd($user);
});
```

#### 钉钉账号密码登录
```
Route::get('dingtalk',function(){
    return Socialite::driver('dingtalk')->scopes('pwd')->redirect();
});
```

