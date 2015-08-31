<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionOutput extends Model {

    public $timestamps = false;

    public function input() {
        return $this->belongsTo('App\Models\TransactionInput', 'transaction_input_id');
    }

    public function transaction() {
        return $this->belongsTo('App\Models\Transaction');
    }

}