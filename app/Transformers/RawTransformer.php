<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;

class RawTransformer extends TransformerAbstract {

    public function transform(Array $data) {
        return $data;
    }

}