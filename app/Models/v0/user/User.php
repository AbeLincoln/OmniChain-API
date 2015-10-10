<?php

namespace App\Models\v0\user;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

    public $timestamps = false;

    public function apiKeys() {
        return $this->hasMany('App\Models\v0\user\apikey\ApiKey');
    }

    public function logins() {
        return $this->hasMany('App\Models\v0\user\login\Login');
    }

}