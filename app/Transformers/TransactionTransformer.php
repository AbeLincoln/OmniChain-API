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
            'hash' => (string) $transaction->hash,
            'version' => (int) $transaction->version,
            'lock_time' => (int) $transaction->lock_time,
            'size' => (int) $transaction->size,
            'block_hash' => (string) $transaction->block_hash,
            'time' => (int) $transaction->time,
            'main_chain' => (boolean) $transaction->longest
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