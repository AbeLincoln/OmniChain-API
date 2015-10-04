<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Transformers\TransactionTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use App\Serializers\ArraySerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class TransactionController extends ApiController {

    public function index(Manager $fractal, TransactionTransformer $transactionTransformer) {
        $transactions = Transaction::orderBy('time', 'desc')->paginate(10);

        $collection = new Collection($transactions, $transactionTransformer);

        $collection->setPaginator(new IlluminatePaginatorAdapter($transactions));

        $data = $fractal->createData($collection)->toArray();

        return $this->respond($data);
    }

    public function show($hash, Manager $fractal, TransactionTransformer $transactionTransformer) {
        $fractal->parseIncludes('inputs.prev_output,outputs');

        $transaction = Transaction::where('hash', $hash)->get()->first();

        if (is_null($transaction)) {
            return $this->setStatusCode(404)->respond(['errors' => ['invalid-transaction']]);
        }

        $item = new Item($transaction, $transactionTransformer);

        $data = $fractal->setSerializer(new ArraySerializer())->createData($item)->toArray();

        return $this->respond($data);
    }

}