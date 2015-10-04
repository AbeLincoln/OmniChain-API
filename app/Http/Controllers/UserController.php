<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Serializers\ArraySerializer;
use App\Transformers\UserTransformer;

class UserController extends DaemonController {

    public function show(Manager $fractal, Request $request, UserTransformer $userTransformer) {
        $user = $request->user();

        $item = new Item($user, $userTransformer);

        $data = $fractal->setSerializer(new ArraySerializer())->createData($item)->toArray();

        return $this->respond($data);
    }

    public function update(Request $request) {
        $validator = \Validator::make($request->all(), [
            'email' => 'email'
        ], [
            'email' => 'invalid-:attribute'
        ]);

        if ($validator->fails()) {
            $errors = [];

            foreach ($validator->errors()->all() as $error) {
                $errors[] = $error;
            }

            return $this->setStatusCode(400)->respond(['errors' => $errors]);
        }

        $user = $request->user();

        if ($request->has('email')) {
            $user->email = $request->get('email');
        }

        $user->update();
    }

}