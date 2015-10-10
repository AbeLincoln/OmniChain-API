<?php

namespace App\Models\v0\user\apikey\ip;

use App\Models\TransformerAbstract;

class IpTransformer extends TransformerAbstract {

    public function transform(Ip $userApiKeyIp) {
        return (string) $userApiKeyIp->ip;
    }

}