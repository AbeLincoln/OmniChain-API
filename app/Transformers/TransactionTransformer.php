<?php

namespace App\Transformers;

use App\Models\Transaction;

class TransactionTransformer extends TransformerAbstract {

    protected $availableIncludes = [
        'inputs',
        'outputs'
    ];

    public function transform(Transaction $transaction) {
        return [
            'hash' => (string) $transaction->hash,
            'version' => (int) $transaction->version,
            'lock-time' => (int) $transaction->lock_time,
            'size' => (int) $transaction->size,
            'block-hash' => (string) $transaction->block_hash,
            'time' => (int) $transaction->time,
            'main-chain' => (boolean) $transaction->longest
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