<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Nbobtc\Command\Command;
use Nbobtc\Http\Client;
use App\Transformers\InfoTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Serializers\ArraySerializer;

class InfoController extends ApiController {

    public function index(Manager $fractal, InfoTransformer $infoTransformer) {
        $client = new Client('http://' . env('WALLET_ABE_USERNAME', '') . ':' . env('WALLET_ABE_PASSWORD', '') . '@' . env('WALLET_ABE_HOST', '') . ':' . env('WALLET_ABE_PORT', ''));

        $miningInfo = json_decode($client->sendCommand(new Command('getmininginfo'))->getBody()->getContents())->result;

        $lastBlock = Block::orderBy('height', 'desc')->first();

        $omc_btc_price = getOption('omc_btc_price');

        $btc_usd_price = getOption('btc_usd_price');

        $item = new Item([$miningInfo, $lastBlock, $omc_btc_price, $btc_usd_price], $infoTransformer);

        $data = $fractal->setSerializer(new ArraySerializer())->createData($item)->toArray();

        return $this->respond($data);
    }

}