<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\TransactionOutput;
use App\Transformers\AddressTransformer;
use App\Transformers\TransactionTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use App\Serializers\ArraySerializer;

class AddressController extends ApiController {

    //TODO: Pagination
    public function show($addr, Manager $fractal, AddressTransformer $addressTransformer, TransactionTransformer $transactionTransformer) {
        $address = Address::where('address_hash', addressToPubkeyHash($addr))->get()->first();

        if (is_null($address)) {
            return $this->setStatusCode(404)->respond(['error' => 'Address not found']);
        }

        $transactions = array();

        $outputs = TransactionOutput::where('address_id', $address->id)->get();

        foreach ($outputs as $output) {
            if (!in_array($output->transaction, $transactions)) {
                $transactions[] = $output->transaction;

                if (!is_null($output->input)) {
                    if (!in_array($output->input->transaction, $transactions)) {
                        $transactions[] = $output->input->transaction;
                    }
                }
            }
        }

        usort($transactions, function($a, $b) {
            return $a['time'] > $b['time'];
        });

        $item = new Item($address, $addressTransformer);

        $collection = new Collection($transactions, $transactionTransformer);

        $data = $fractal->setSerializer(new ArraySerializer())->createData($item)->toArray();

        $data['transactions'] = $fractal->setSerializer(new ArraySerializer())->createData($collection)->toArray();

        return $this->respond($data);
    }

}