<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model {

    public $timestamps = false;

    public function apiKeys() {
        return $this->hasMany('App\Models\UserApiKey');
    }

}