<?php

namespace App\Models\v0\block\transaction\output;

use Illuminate\Database\Eloquent\Model;

class Output extends Model {

    public $timestamps = false;
    protected $connection = 'abe';

    public function input() {
        return $this->belongsTo('App\Models\v0\block\transaction\input\Input');
    }

    public function transaction() {
        return $this->belongsTo('App\Models\v0\block\transaction\Transaction');
    }

    public function address() {
        return $this->belongsTo('App\Models\v0\block\transaction\output\address\Address');
    }

}