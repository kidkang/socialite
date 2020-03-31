<?php

namespace Yjtec\Socialite\Models;
class Apple extends AbstractModel
{
    protected $table = 'socialite_apple';
    protected $fillable = ['sub','client_id'];
    public function uniqUser(array $user){
        return $this->where('sub',$user['sub'])->first();
    }
}
