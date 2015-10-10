<?php

namespace App\Models\v0\block\transaction\input;

use App\Models\TransformerAbstract;
use App\Models\v0\block\transaction\output\OutputTransformer;

class InputTransformer extends TransformerAbstract {

    protected $defaultIncludes = [
        'prev_output'
    ];

    public function transform(Input $transactionInput) {
        return [
            'n' => (int) $transactionInput->n,
            'script' => (string) $transactionInput->script
        ];
    }

    public function includePrevOutput(Input $transactionInput) {
        $output = $transactionInput->output;

        return is_null($output) ? $output : $this->item($output, new OutputTransformer);
    }

}