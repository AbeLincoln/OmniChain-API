<?php

namespace App\Models\v0\block;

use Illuminate\Database\Eloquent\Model;

class Block extends Model {

    public $timestamps = false;
    protected $connection = 'abe';

    public function transactions() {
        return $this->hasMany('App\Models\v0\block\transaction\Transaction');
    }

    public function nextBlock() {
        return $this->hasOne('App\Models\v0\block\Block', 'previous_block_id');
    }

    public function previousBlock() {
        return $this->belongsTo('App\Models\v0\block\Block', 'previous_block_id');
    }

}