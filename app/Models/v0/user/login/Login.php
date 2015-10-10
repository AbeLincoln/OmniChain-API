<?php

namespace App\Models\v0\user\login;

use Illuminate\Database\Eloquent\Model;

class Login extends Model {

    public $timestamps = false;
    protected $fillable = ['time', 'ip', 'user_id', 'valid'];

    public function user() {
        return $this->belongsTo('App\Models\v0\user\User');
    }

}