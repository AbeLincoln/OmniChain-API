<?php

namespace App\Http\Controllers\v0\users;

use App\Http\Controllers\ApiController;
use App\Models\v0\Login;
use App\Models\v0\user\User;
use App\Models\v0\user\UserTransformer;
use App\Serializers\ArraySerializer;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;

class AuthController extends ApiController {

    public function login(Request $request, Manager $fractal, UserTransformer $userTransformer) {
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

        $ipCheck = Login::where(['ip' => $request->ip(), 'valid' => false])->where('time', '>', date('Y-m-d H:i:s', time() - (60 * 60 * 24)))->get();

        if (count($ipCheck) > 15) {
            Login::create(['time' => date('Y-m-d H:i:s'), 'ip' => $request->ip(), 'valid' => false]);

            return $this->setStatusCode(403)->respond(['errors' => ['ip-banned']]);
        }

        $user = User::where(['username' => $request->get('username')])->orWhere(['email' => $request->get('username')])->first();

        if (is_null($user)) {
            return $this->setStatusCode(404)->respond(['errors' => ['user-not-found']]);
        }

        $userCheck = Login::where(['user_id' => $user->id, 'valid' => false])->where('time', '>', time() - (60 * 15))->get();

        if (count($userCheck) > 15) {
            return $this->setStatusCode(403)->respond(['errors' => ['user-locked']]);
        }

        if (!\Hash::check($request->get('password'), $user->password)) {
            Login::create(['time' => date('Y-m-d H:i:s'), 'ip' => $request->ip(), 'user_id' => $user->id, 'valid' => false]);

            return $this->setStatusCode(403)->respond(['errors' => ['invalid-password']]);
        }

        $user->session = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
        $user->session_expire_time = date('Y-m-d H:i:s', time() + 60 * 60);

        $user->update();

        Login::create(['time' => date('Y-m-d H:i:s'), 'ip' => $request->ip(), 'user_id' => $user->id, 'valid' => true]);

        $item = new Item($user, $userTransformer);

        $data = $fractal->setSerializer(new ArraySerializer())->createData($item)->toArray();

        return $this->respond($data);
    }

    public function register(Request $request, Manager $fractal, UserTransformer $userTransformer) {
        $validator = \Validator::make($request->all(), [
            'username' => 'required|unique:users|min:3|max:30',
            'password' => 'required',
            'email' => 'email',
        ], [
            'required' => 'no-:attribute-provided',
            'unique' => ':attribute-taken',
            'min' => ':attribute-too-short',
            'max' => ':attribute-too-long',
            'email' => 'invalid-:attribute'
        ]);

        if ($validator->fails()) {
            $errors = [];

            foreach ($validator->errors()->all() as $error) {
                $errors[] = $error;
            }

            return $this->setStatusCode(400)->respond(['errors' => $errors]);
        }

        $user = new User();

        $user->username = $request->get('username');
        $user->email = $request->get('email') ?: '';
        $user->password = \Hash::make($request->get('password'));
        $user->session = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
        $user->session_expire_time = date('Y-m-d H:i:s', time() + 60 * 60);

        $user->save();

        $item = new Item($user, $userTransformer);

        $data = $fractal->setSerializer(new ArraySerializer())->createData($item)->toArray();

        return $this->respond($data);
    }

}