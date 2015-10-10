<?php

namespace App\Models\v0\block\transaction;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model {

    public $timestamps = false;
    protected $connection = 'abe';

    public function inputs() {
        return $this->hasMany('App\Models\v0\block\transaction\input\Input');
    }

    public function outputs() {
        return $this->hasMany('App\Models\v0\block\transaction\output\Output');
    }

    public function block() {
        return $this->belongsTo('App\Models\v0\block\Block');
    }

}