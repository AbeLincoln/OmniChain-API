<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionInput extends Model {

    public $timestamps = false;

    public function output() {
        return $this->hasOne('App\Models\TransactionOutput');
    }

}