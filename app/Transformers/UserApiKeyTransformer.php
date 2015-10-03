<?php

namespace App\Transformers;

use App\Models\UserApiKey;

class UserApiKeyTransformer extends TransformerAbstract {

    protected $defaultIncludes = [
        'actions',
        'ips'
    ];

    public function transform(UserApiKey $userApiKey) {
        return [
            'id' => (int) $userApiKey->id,
            'key' => (string) $userApiKey->key
        ];
    }

    public function includeActions(UserApiKey $userApiKey) {
        $actions = $userApiKey->actions;

        return $this->collection($actions, new UserApiKeyActionTransformer);
    }

    public function includeIps(UserApiKey $userApiKey) {
        $ips = $userApiKey->ips;

        return $this->collection($ips, new UserApiKeyIpTransformer);
    }

}