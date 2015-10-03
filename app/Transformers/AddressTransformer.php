<?php

namespace App\Transformers;

use App\Models\Address;
use League\Fractal\TransformerAbstract;

class AddressTransformer extends TransformerAbstract {

    protected $defaultIncludes = [
        'transactions'
    ];

    public function transform(Address $address) {
        return [
            'address' => (string) pubkeyHashToAddress($address->address_hash),
            'pubkey' => (string) $address->pubkey,
            'pubkey-hash' => (string) $address->address_hash,
            'transactions-in' => (int) $address->txin_count,
            'transactions-out' => (int) $address->txout_count,
            'transactions-in-value' => (int) $address->txin_value,
            'transactions-out-value' => (int) $address->txout_value,
            'balance' => (int) $address->txin_value - $address->txout_value
        ];
    }

    public function includeTransactions(Address $address) {
        $transactions = $address->transactions;

        return $this->collection($transactions, new TransactionTransformer);
    }

}