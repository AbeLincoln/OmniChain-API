<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Block extends Model {

    public $timestamps = false;

    public function transactions() {
        return $this->hasMany('App\Models\Transaction');
    }

}