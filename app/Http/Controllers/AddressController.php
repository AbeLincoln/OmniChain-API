<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\TransactionOutput;
use App\Transformers\AddressTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Serializers\ArraySerializer;

class AddressController extends ApiController {

    public function show($addr, Manager $fractal, AddressTransformer $addressTransformer) {
        if (!isAddress($addr)) {
            return $this->setStatusCode(404)->respond(['errors' => ['invalid-address']]);
        }

        $address = Address::where('address_hash', addressToPubkeyHash($addr))->get()->first();

        if (is_null($address)) {
            return $this->setStatusCode(404)->respond(['errors' => ['not-on-network']]);
        }

        $transactions = array();

        $transactionInputs = array();

        $txinCount = 0;
        $txinValue = 0;

        $txoutCount = 0;
        $txoutValue = 0;

        $outputs = TransactionOutput::where('address_id', $address->id)->get();

        foreach ($outputs as $output) {
            $txinValue += $output->value;

            if (!in_array($output->transaction, $transactions)) {
                $transactions[] = $output->transaction;

                $txinCount++;

                if (!is_null($output->input)) {
                    $txoutValue += $output->value;

                    if (!in_array($output->input->transaction, $transactions)) {
                        $transactions[] = $output->input->transaction;
                    }

                    if (!in_array($output->input->transaction, $transactionInputs)) {
                        $transactionInputs[] = $output->input->transaction;

                        $txoutCount++;
                    }
                }
            }
        }

        usort($transactions, function ($a, $b) {
            return $a['time'] < $b['time'];
        });

        $address['transactions'] = $transactions;
        $address['txin_count'] = $txinCount;
        $address['txin_value'] = $txinValue;
        $address['txout_count'] = $txoutCount;
        $address['txout_value'] = $txoutValue;

        $item = new Item($address, $addressTransformer);

        $data = $fractal->setSerializer(new ArraySerializer())->createData($item)->toArray();

        return $this->respond($data);
    }

    public function _validate($addr) {
        return $this->respond(['valid' => (boolean) isAddress($addr)]);
    }

}