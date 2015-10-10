<?php

namespace App\Http\Controllers\v0\addresses;

use App\Http\Controllers\DaemonController;
use App\Models\v0\block\transaction\output\address\Address;
use App\Models\v0\block\transaction\output\address\AddressTransformer;
use App\Serializers\ArraySerializer;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;

class AddressController extends DaemonController {

    public function show($addr, Manager $fractal, AddressTransformer $addressTransformer) {
        $fractal->parseIncludes('transactions.inputs,transactions.outputs');
        if (!isAddress($addr)) {
            return $this->setStatusCode(404)->respond(['errors' => ['invalid-address']]);
        }

        $address = Address::where('pubkey_hash', addressToPubkeyHash($addr))->get()->first();

        if (is_null($address)) {
            return $this->setStatusCode(404)->respond(['errors' => ['not-on-network']]);
        }

        $transactions = [];
        $address->txin_count = 0;
        $address->txin_value = 0;
        $address->txout_count = 0;
        $address->txout_value = 0;

        foreach ($address->outputs as $output) {
            $transaction = $output->transaction;

            $address->txin_value += $output->value;

            if (!in_array($transaction, $transactions)) {
                $address->txin_count++;

                $transactions[] = $transaction;
            }

            /*
            $input = $output->input;

            if ($input != null) {
                $transaction = $input->transaction;

                //$address->txout_count ++;
                //$address->txout_value = 0;

                if (!in_array($transaction, $transactions)) {
                    $transactions[] = $transaction;
                }
            }
            */
        }

        usort($transactions, function ($a, $b) {
            return $a['time'] < $b['time'];
        });

        $address->transactions = $transactions;

        $item = new Item($address, $addressTransformer);

        $data = $fractal->setSerializer(new ArraySerializer())->createData($item)->toArray();

        return $this->respond($data);
    }

    //TODO: Should simply be get argument of show() to only validate
    public function val($addr) {
        return $this->respond(['valid' => (boolean) isAddress($addr)]);
    }

    //TODO: Should validate before verifying; verify input variables; use address from URL, not GET
    public function validateMessage(Request $request) {
        $validator = \Validator::make($request->all(), [
            'address' => 'required,array',
            'ips' => 'required,array'
        ], [
            'array' => ':attribute-not-array',
            'required' => ':attribute-required'
        ]);

        if ($validator->fails()) {
            $errors = [];

            foreach ($validator->errors()->all() as $error) {
                $errors[] = $error;
            }

            return $this->setStatusCode(400)->respond(['errors' => $errors]);
        }

        $verifyMessage = sendRpcCommand($this->client, 'verifymessage', [$request->input('address', ''), $request->input('signature', ''), $request->input('message', '')]);

        if ($verifyMessage === false) {
            return $this->setStatusCode(500)->respond(['errors' => ['unknown-error']]);
        }

        return $this->respond(['verified' => (boolean) $verifyMessage->result]);
    }

}