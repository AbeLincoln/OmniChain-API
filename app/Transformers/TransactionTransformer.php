<?php

namespace App\Transformers;

use App\Models\Transaction;
use League\Fractal\TransformerAbstract;

class TransactionTransformer extends TransformerAbstract {

    protected $availableIncludes = [
        'inputs',
        'outputs'
    ];

    public function transform(Transaction $transaction) {
        return [
            'hash' => $transaction->hash,
            'version' => $transaction->version,
            'lock_time' => $transaction->lock_time,
            'size' => $transaction->size,
            'block_hash' => $transaction->block_hash,
            'time' => $transaction->time,
            'main_chain' => $transaction->longest == 1
        ];
    }

    public function includeInputs(Transaction $transaction) {
        $inputs = $transaction->inputs;

        return $this->collection($inputs, new TransactionInputTransformer);
    }

    public function includeOutputs(Transaction $transaction) {
        $outputs = $transaction->outputs;

        return $this->collection($outputs, new TransactionOutputTransformer);
    }

}