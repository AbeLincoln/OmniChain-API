<?php

namespace App\Http\Controllers;

use Nbobtc\Command\Command;
use Nbobtc\Http\Client;
use Illuminate\Http\Request;
use App\Transformers\RawTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Serializers\ArraySerializer;

class WalletController extends ApiController {

    private $client;

    public function __construct() {
        $this->client = new Client('http://' . env('OMCD_WALLET_USERNAME', '') . ':' . env('OMCD_WALLET_PASSWORD', '') . '@' . env('OMCD_WALLET_HOST', '') . ':' . env('OMCD_WALLET_PORT', ''));
    }

    public function register() {

    }
    
    public function login() {
  
    }
    
    public function show() {

    }

    public function generateAddress() {

    }

    public function send($id, Manager $fractal, RawTransformer $rawTransformer, Request $request) {
        if (!$request->has('amount') || ($amount = $request->input('amount')) <= 0) {
            return $this->setStatusCode(400)->respond(['error' => 'invalid-amount']);
        }

        if (!$request->has('address') || !isAddress($address = $request->input('address'))) {
            return $this->setStatusCode(400)->respond(['error' => 'invalid-address']);
        }

        //send
    }

    public function importAddress() {

    }

    public function signMessage() {

    }

    public function update() {

    }

}