<?php

namespace App\Transformers;

use App\Models\UserApiKeyAction;

class UserApiKeyActionTransformer extends TransformerAbstract {

    public function transform(UserApiKeyAction $userApiKeyAction) {
        return (int) $userApiKeyAction->action;
    }

}