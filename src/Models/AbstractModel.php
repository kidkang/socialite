<?php

namespace Yjtec\Socialite\Models;
use Illuminate\Database\Eloquent\Model;
abstract class AbstractModel extends Model{
    abstract public function uniqUser(array $user);
}