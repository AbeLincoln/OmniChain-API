<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model {

    public $timestamps = false;

    public function transactions() {
        return $this->hasManyThrough('App\Models\Transaction', 'App\Models\TransactionOutput');
    }

}