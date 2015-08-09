<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Transformers\AddressTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Serializers\ArraySerializer;

class AddressController extends ApiController {

    public function show($addr, Manager $fractal, AddressTransformer $addressTransformer) {
        $address = Address::where('address', addressToPubkeyHash($addr))->get()->first();

        if (is_null($address)) {
            return $this->setStatusCode(404)->respond(['error' => 'Address not found']);
        }

        $item = new Item($address, $addressTransformer);

        $data = $fractal->setSerializer(new ArraySerializer())->createData($item)->toArray();

        return $this->respond($data);
    }

}