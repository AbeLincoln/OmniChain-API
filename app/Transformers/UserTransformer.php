<?php

namespace App\Transformers;

use App\Models\User;

class UserTransformer extends TransformerAbstract {

    protected $availableIncludes = [
        'api-keys',
        'transactions'
    ];

    public function transform(User $user) {
        return [
            'id' => (int) $user->id,
            'username' => (string) $user->username,
            'email' => (string) $user->email,
            'session' => (string) $user->session,
            'session-expire-time' => (string) date('Y-m-d H:i:s', strtotime($user->session_create_time) + (60 * 60)),
            'address-create-time' => (string) $user->address_create_time,
            'balance' => (int) 0,
            'balance-pending' => (int) 0,
            'transactions-in' => (int) 0,
            'transactions-out' => (int) 0,
            'transactions-in-value' => (int) 0,
            'transactions-out-value' => (int) 0,
        ];
    }

    public function includeApiKeys(User $user) {
        $apiKeys = $user->apiKeys;

        return $this->collection($apiKeys, new UserApiKeyTransformer);
    }

    public function includeTransactions(User $user) {
        $transactions = $user->transactions;

        return $this->collection($transactions, new TransactionTransformer);
    }

}