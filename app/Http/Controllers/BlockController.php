<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Transformers\BlockTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use App\Serializers\ArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class BlockController extends ApiController {

    //TODO: Main chain only list option
    public function index(Manager $fractal, BlockTransformer $blockTransformer) {
        $blocks = Block::paginate(10);

        $collection = new Collection($blocks, $blockTransformer);

        $collection->setPaginator(new IlluminatePaginatorAdapter($blocks));

        $data = $fractal->createData($collection)->toArray();

        return $this->respond($data);
    }

    //TODO: Main chain over others
    public function show($hash, Manager $fractal, BlockTransformer $blockTransformer) {
        $fractal->parseIncludes('transactions.inputs,transactions.outputs');

        $block = Block::where('hash', $hash)->get()->first();

        if (is_null($block)) {
            return $this->setStatusCode(404)->respond(['error' => 'Block not found']);
        }

        $item = new Item($block, $blockTransformer);

        $data = $fractal->setSerializer(new ArraySerializer())->createData($item)->toArray();

        return $this->respond($data);
    }

}