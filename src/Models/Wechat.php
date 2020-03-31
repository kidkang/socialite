<?php

namespace Yjtec\Socialite\Models;
class Wechat extends AbstractModel
{
    protected $table = 'socialite_wechat';
    protected $fillable = ['client_id','openid','unionid','nickname','sex','city','province','country','headimgurl'];

    public function uniqUser(array $user){
        return $this->where('openid',$user['openid'])->first();
    }
    public function setNicknameAttribute($value){
        $this->attributes['nickname'] = userTextEncode($value);
    }

    public function getNicknameAttribute(){
        return userTextDecode($this->attributes['nickname']);
    }
    public function socialites(){
        return $this->morphMany('Yjtec\Socialite\Models\Socialite','socialitetable');
    }
}
