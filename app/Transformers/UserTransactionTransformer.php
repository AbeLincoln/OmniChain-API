<?php

namespace App\Transformers;

use App\Models\User;

class UserTransactionTransformer extends TransformerAbstract {

    protected $availableIncludes = [
        'transactions'
    ];

    public function transform(User $user) {
        return [
            'balance' => (int) $user->balance,
            'balance-pending' => (int) $user->balance_pending,
            'transactions-in' => (int) $user->transactions_in,
            'transactions-out' => (int) $user->transactions_ouut,
            'transactions-in-value' => (int) $user->transactions_in_value,
            'transactions-out-value' => (int) $user->transactions_out_value
        ];
    }

    public function includeTransactions(User $user) {
        $transactions = $user->transactions;

        return $this->collection($transactions, new TransactionTransformer);
    }

}