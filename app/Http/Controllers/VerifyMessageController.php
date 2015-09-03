<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Nbobtc\Command\Command;
use Nbobtc\Http\Client;
use App\Transformers\RawTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Serializers\ArraySerializer;

class VerifyMessageController extends ApiController {

    public function index(Manager $fractal, RawTransformer $rawTransformer, Request $request) {
        $client = new Client('http://' . $_ENV['WALLET_ABE_USERNAME'] . ':' . $_ENV['WALLET_ABE_PASSWORD'] . '@' . $_ENV['WALLET_ABE_HOST'] . ':' . $_ENV['WALLET_ABE_PORT']);

        $verifyMessage = json_decode($client->sendCommand(new Command('verifymessage', [$request->input('address'), $request->input('signature'), $request->input('message')]))->getBody()->getContents())->result;

        $item = new Item(['verified' => (boolean) $verifyMessage], $rawTransformer);

        $data = $fractal->setSerializer(new ArraySerializer())->createData($item)->toArray();

        return $this->respond($data);
    }

}