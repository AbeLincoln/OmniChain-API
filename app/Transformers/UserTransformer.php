<?php

namespace App\Transformers;

use App\Models\User;

class UserTransformer extends TransformerAbstract {

    public function transform(User $user) {
        return [
            'id' => (int) $user->id,
            'username' => (string) $user->username,
            'email' => (string) $user->email,
            'session' => (string) $user->session,
            'session-expire-time' => (string) $user->session_expire_time,
            'next-address-generate-time' => (string) $user->next_address_generate_time
        ];
    }

}