<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Serializers\ArraySerializer;
use App\Transformers\UserTransformer;

class UserController extends DaemonController {

    public function show(Manager $fractal, Request $request, UserTransformer $userTransformer) {
        $fractal->parseIncludes('api-keys');

        //$accountAddresses = sendRpcCommand($this->client, 'getaddressesbyaccount', [$request->user()->id]);

        $user = $request->user();

        $item = new Item($user, $userTransformer);

        $data = $fractal->setSerializer(new ArraySerializer())->createData($item)->toArray();

        return $this->respond($data);
    }

    public function generateAddress() {

    }

    public function send(Manager $fractal, Request $request) {
        $validator = \Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required'
        ], [
            'required' => 'no-:attribute-provided'
        ]);

        if ($validator->fails()) {
            $errors = [];

            foreach ($validator->errors()->all() as $error) {
                $errors[] = $error;
            }

            return $this->setStatusCode(400)->respond(['errors' => $errors]);
        }

        if (!$request->has('amount') || !is_numeric($amount = $request->input('amount')) || $amount <= 0) {
            return $this->setStatusCode(400)->respond(['errors' => ['invalid-amount']]);
        }

        if (!$request->has('address') || !isAddress($address = $request->input('address'))) {
            return $this->setStatusCode(400)->respond(['errors' => ['invalid-address']]);
        }

        $accountAddresses = sendRpcCommand($this->client, 'getaddressesbyaccount', [$request->user()->id]);

        var_dump($accountAddresses);
    }

    public function importAddress() {

    }

    public function signMessage() {

    }

    public function update() {

    }

}