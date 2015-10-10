<?php

namespace App\Models\v0\user\apikey;

use App\Models\TransformerAbstract;
use App\Models\v0\user\apikey\action\ActionTransformer;
use App\Models\v0\user\apikey\ip\IpTransformer;

class ApiKeyTransformer extends TransformerAbstract {

    protected $defaultIncludes = [
        'actions',
        'ips'
    ];

    public function transform(ApiKey $userApiKey) {
        return [
            'id' => (int) $userApiKey->id,
            'key' => (string) $userApiKey->key
        ];
    }

    public function includeActions(ApiKey $userApiKey) {
        $actions = $userApiKey->actions;

        return $this->collection($actions, new ActionTransformer);
    }

    public function includeIps(ApiKey $userApiKey) {
        $ips = $userApiKey->ips;

        return $this->collection($ips, new IpTransformer);
    }

}