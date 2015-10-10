<?php
use App\Http\Controllers\ApiController;

//TODO: Unfinished
class ApiKeyController extends ApiController {



}
/*
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
*/