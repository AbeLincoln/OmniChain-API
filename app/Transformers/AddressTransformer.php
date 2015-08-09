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
            'address' => $address->address
        ];
    }

    public function includeTransactions(Address $address) {
        $transactions = $address->transactions;

        return $this->collection($transactions, new TransactionTransformer);
    }

}