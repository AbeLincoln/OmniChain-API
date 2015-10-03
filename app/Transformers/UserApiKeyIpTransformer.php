<?php

namespace App\Transformers;

use App\Models\UserApiKeyIp;

class UserApiKeyIpTransformer extends TransformerAbstract {

    public function transform(UserApiKeyIp $userApiKeyIp) {
        return (string) $userApiKeyIp->ip;
    }

}