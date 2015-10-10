<?php

namespace App\Models\v0\block\transaction\output\address;

use Illuminate\Database\Eloquent\Model;

class Address extends Model {

    public $timestamps = false;
    protected $connection = 'abe';

    public function outputs() {
        return $this->hasMany('App\Models\v0\block\transaction\output\Output');
    }

}