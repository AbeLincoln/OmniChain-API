<?php

namespace App\Transformers;

use App\Models\TransactionOutput;
use League\Fractal\TransformerAbstract;

class TransactionOutputTransformer extends TransformerAbstract {

    public function transform(TransactionOutput $transactionOutput) {
        return [
            'n' => $transactionOutput->n,
            'value' => $transactionOutput->value,
            'script' => $transactionOutput->script,
            'address' => pubkeyHashToAddress($transactionOutput->address),
            'spent' => isset($transactionOutput->spent)
        ];
    }

}