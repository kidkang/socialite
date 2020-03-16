<?php

namespace Yjtec\Socialite\Models;

use Illuminate\Database\Eloquent\Model;

class Socialite extends Model
{
    protected $fillable = ['socialitetable_id','socialitetable_type'];

    
    public function socialitetable(){
        return $this->morphTo();
    }
}
