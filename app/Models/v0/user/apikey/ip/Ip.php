<?php

namespace App\Models\v0\user\apikey\ip;

use Illuminate\Database\Eloquent\Model;

class Ip extends Model {

    public $timestamps = false;
    protected $table = 'user_api_key_ips';

}