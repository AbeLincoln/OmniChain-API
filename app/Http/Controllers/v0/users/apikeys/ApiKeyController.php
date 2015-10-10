<?php
namespace App\Http\Controllers\v0\users\apikeys;

use App\Http\Controllers\ApiController;
use App\Models\v0\user\apikey\ApiKeyTransformer;
use App\Serializers\ArraySerializer;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

//TODO: Unfinished
class ApiKeyController extends ApiController {

    public function index(Manager $fractal, Request $request, ApiKeyTransformer $apiKeyTransformer) {
        $apiKeys = $request->user()->apiKeys;

        $collection = new Collection($apiKeys, $apiKeyTransformer);

        $data = $fractal->setSerializer(new ArraySerializer())->createData($collection)->toArray();

        return $this->respond($data);
    }

    public function create(Request $request) {
        $validator = \Validator::make($request->all(), [
            'actions' => 'required,array',
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

        /*
        $user = $request->user();

        if ($request->has('email')) {
            $user->email = $request->get('email');
        }

        $user->update();*/
        echo 'mhm';
    }

    public function show() {

    }

    public function update() {

    }

    public function delete() {

    }

}