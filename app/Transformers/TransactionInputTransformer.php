<?php

namespace App\Transformers;

use App\Models\TransactionInput;

class TransactionInputTransformer extends TransformerAbstract {

    protected $defaultIncludes = [
        'prev_output'
    ];

    public function transform(TransactionInput $transactionInput) {
        return [
            'n' => (int) $transactionInput->n,
            'script' => (string) $transactionInput->script
        ];
    }

    public function includePrevOutput(TransactionInput $transactionInput) {
        $output = $transactionInput->output;

        return is_null($output) ? $output : $this->item($output, new TransactionOutputTransformer);
    }

}