<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {

    public $timestamps = false;
    protected $connection = 'abe';

    public function inputs() {
        return $this->hasMany('App\Models\TransactionInput');
    }

    public function outputs() {
        return $this->hasMany('App\Models\TransactionOutput');
    }

    public function address() {
        return $this->hasOne('App\Models\Address');
    }

}