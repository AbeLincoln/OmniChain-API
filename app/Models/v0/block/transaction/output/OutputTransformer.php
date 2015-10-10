<?php

namespace App\Models\v0\block\transaction\output;

use App\Models\TransformerAbstract;

class OutputTransformer extends TransformerAbstract {

    public function transform(Output $transactionOutput) {
        return [
            'n' => (int) $transactionOutput->n,
            'value' => (int) $transactionOutput->value,
            'script' => (string) $transactionOutput->script,
            'address' => (string) pubkeyHashToAddress($transactionOutput->address->pubkey_hash),
            'spent' => (boolean) isset($transactionOutput->spent)
        ];
    }

}