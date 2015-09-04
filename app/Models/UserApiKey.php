<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserApiKey extends Model {

    public $timestamps = false;

    public function ips() {
        return $this->hasMany('App\Models\UserApiKeyIp');
    }

    public function actions() {
        return $this->hasMany('App\Models\UserApiKeyAction');
    }

}