<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionInput extends Model {

    public $timestamps = false;
    protected $connection = 'abe';

    public function output() {
        return $this->hasOne('App\Models\TransactionOutput');
    }

    public function transaction() {
        return $this->belongsTo('App\Models\Transaction');
    }

}