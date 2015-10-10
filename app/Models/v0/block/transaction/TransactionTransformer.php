<?php

namespace App\Models\v0\block\transaction;

use App\Models\TransformerAbstract;
use App\Models\v0\block\transaction\input\InputTransformer;
use App\Models\v0\block\transaction\output\OutputTransformer;

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
            'block-hash' => (string) $transaction->block->hash,
            'time' => (int) $transaction->block->time,
            'main-chain' => (boolean) $transaction->block->longest
        ];
    }

    public function includeInputs(Transaction $transaction) {
        $inputs = $transaction->inputs;

        return $this->collection($inputs, new InputTransformer);
    }

    public function includeOutputs(Transaction $transaction) {
        $outputs = $transaction->outputs;

        return $this->collection($outputs, new OutputTransformer);
    }

}