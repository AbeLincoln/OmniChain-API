<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Transformers\InfoTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Serializers\ArraySerializer;

class InfoController extends DaemonController {

    public function index(Manager $fractal, InfoTransformer $infoTransformer) {
        $miningInfo = sendRpcCommand($this->client, 'getmininginfo');

        if ($miningInfo === false) {
            return $this->setStatusCode(500)->respond(['errors' => ['unknown-error']]);
        }

        $lastBlock = Block::orderBy('height', 'desc')->first();

        $omc_btc_price = getOption('omc_btc_price');

        $btc_usd_price = getOption('btc_usd_price');

        $item = new Item((object) ['mining_info' => $miningInfo->result, 'last_block' => $lastBlock, 'omc_btc_price' => $omc_btc_price, 'btc_usd_price' => $btc_usd_price], $infoTransformer);

        $data = $fractal->setSerializer(new ArraySerializer())->createData($item)->toArray();

        return $this->respond($data);
    }

}