<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Login extends Model {

    public $timestamps = false;

    protected $fillable = ['time', 'ip', 'user_id', 'valid'];

}