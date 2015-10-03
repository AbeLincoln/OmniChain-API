<?php

namespace App\Transformers;

use App\Models\TransactionOutput;

class TransactionOutputTransformer extends TransformerAbstract {

    public function transform(TransactionOutput $transactionOutput) {
        return [
            'n' => (int) $transactionOutput->n,
            'value' => (int) $transactionOutput->value,
            'script' => (string) $transactionOutput->script,
            'address' => (string) pubkeyHashToAddress($transactionOutput->address_hash),
            'spent' => (boolean) isset($transactionOutput->spent)
        ];
    }

}