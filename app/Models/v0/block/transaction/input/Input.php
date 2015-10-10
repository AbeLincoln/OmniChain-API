<?php

namespace App\Models\v0\block\transaction\input;

use Illuminate\Database\Eloquent\Model;

class Input extends Model {

    public $timestamps = false;
    protected $connection = 'abe';

    public function output() {
        return $this->hasOne('App\Models\v0\block\transaction\output\Output');
    }

    public function transaction() {
        return $this->belongsTo('App\Models\v0\block\transaction\Transaction');
    }

}