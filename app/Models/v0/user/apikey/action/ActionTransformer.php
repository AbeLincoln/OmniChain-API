<?php

namespace App\Models\v0\user\apikey\action;

use App\Models\TransformerAbstract;

class ActionTransformer extends TransformerAbstract {

    public function transform(Action $userApiKeyAction) {
        return (int) $userApiKeyAction->action;
    }

}