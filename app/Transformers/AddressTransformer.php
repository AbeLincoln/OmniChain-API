<?php

namespace App\Transformers;

use App\Models\Address;
use League\Fractal\TransformerAbstract;

class AddressTransformer extends TransformerAbstract {

    public function transform(Address $address) {
        return [
            'address' => pubkeyHashToAddress($address->address_hash),
            'pubkey' => $address->pubkey,
            'pubkey_hash' => $address->address_hash
        ];
    }

}