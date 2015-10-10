<?php

namespace App\Http\Controllers\v0\users;

use App\Http\Controllers\DaemonController;
use App\Models\v0\user\UserTransformer;
use App\Serializers\ArraySerializer;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;

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