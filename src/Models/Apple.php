<?php

namespace Yjtec\Socialite\Models;

use Illuminate\Database\Eloquent\Model;

class Apple extends Model
{
    protected $table = 'socialite_apple';
    protected $fillable = ['sub','client_id'];
}
