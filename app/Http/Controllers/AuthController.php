<?php

namespace App\Http\Controllers;

use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use App\Models\Login;
use App\Models\User;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use App\Serializers\ArraySerializer;

class AuthController extends ApiController {

    public function login(Request $request, UserTransformer $userTransformer, Manager $fractal) {
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

            return response()->json(['errors' => ['ip-banned']], 401);
        }

        $user = User::where(['username' => $request->get('username')])->orWhere(['email' => $request->get('username')])->first();

        if (is_null($user)) {
            return $this->setStatusCode(401)->respond(['errors' => ['user-not-found']]);
        }

        $userCheck = Login::where(['user_id' => $user->id, 'valid' => false])->where('time', '>', time() - (60 * 15))->get();

        if (count($userCheck) > 15) {
            return $this->setStatusCode(401)->respond(['errors' => ['user-locked']]);
        }

        if (!\Hash::check($request->get('password'), $user->password)) {
            Login::create(['time' => date('Y-m-d H:i:s'), 'ip' => $request->ip(), 'user_id' => $user->id, 'valid' => false]);

            return $this->setStatusCode(401)->respond(['errors' => ['invalid-password']]);
        }

        $user->session = hash('sha512', uniqid(mt_rand(1, mt_getrandmax()), true));
        $user->session_create_time = date('Y-m-d H:i:s');

        $user->update();

        Login::create(['time' => date('Y-m-d H:i:s'), 'ip' => $request->ip(), 'user_id' => $user->id, 'valid' => true]);

        $item = new Item($user, $userTransformer);

        $data = $fractal->setSerializer(new ArraySerializer())->createData($item)->toArray();

        return $this->respond($data);
    }

    public function register(Request $request, UserTransformer $userTransformer, Manager $fractal) {
        $validator = \Validator::make($request->all(), [
            'username' => 'required|unique:users|min:3|max:30',
            'password' => 'required',
            'email' => 'email',
        ], [
            'required' => 'no-:attribute-provided',
            'unique' => ':attribute-taken',
            'min' => ':attribute-too-short',
            'max' => ':attribute-too-long',
            'email' => 'invalid-email'
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
        $user->session_create_time = date('Y-m-d H:i:s');

        $user->save();

        $item = new Item($user, $userTransformer);

        $data = $fractal->setSerializer(new ArraySerializer())->createData($item)->toArray();

        return $this->respond($data);
    }

}