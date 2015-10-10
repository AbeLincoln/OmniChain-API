<?php

namespace App\Models\v0\user\apikey;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model {

    public $timestamps = false;
    protected $table = 'user_api_keys';

    public function ips() {
        return $this->hasMany('App\Models\v0\user\apikey\ip\Ip');
    }

    public function actions() {
        return $this->hasMany('App\Models\v0\user\apikey\action\Action');
    }

    public function user() {
        return $this->belongsTo('App\Models\v0\user\User');
    }

}