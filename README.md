# socialite

社会化登陆包

## 使用方法

### 配置

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

### 使用

#### 微信公众平台登录

```
Route::get('wechat',function(){
    return Socialite::driver('wechat')->redirect();
});

Route::get('wechat/callback',function(){
    $user = Socialite::driver('wechat')->user();

    dd($user);
});
```

#### 微信app登陆

```
$user = Socialite::driver('wechat_app')->user();
    dd($user);
```

#### github授权登陆

```
Route::get('soc',function(){
    return Socialite::driver('github')->redirect();
});

Route::get('callback',function(){
    $user = Socialite::driver('github')->user();
    dd($user);
});
```

#### github授权token登陆

`$user = Socialite::driver('github')->userFromToken('2e3d36b286fc051b7641c53c0f50d1bc74ea35a4');`


