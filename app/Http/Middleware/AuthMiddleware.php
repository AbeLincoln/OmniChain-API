<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\Login;
use Closure;

class AuthMiddleware {

    public function handle($request, Closure $next) {
        $ipCheck = Login::where(['ip' => $request->ip(), 'valid' => false])->where('time', '>', date('Y-m-d H:i:s', time() - (60 * 60 * 24)))->get();

        if (count($ipCheck) > 15) {
            return response()->json(['error' => 'ip-banned'], 401);
        }

        $user = User::find($request->route()[2]['id']);

        if (is_null($user)) {
            return response()->json(['error' => 'invalid-user-id'], 401);
        }

        $userCheck = Login::where(['user_id' => $user->id, 'valid' => false])->where('time', '>', time() - (60 * 15))->get();

        if (count($userCheck) > 15) {
            return response()->json(['error' => 'user-locked'], 401);
        }

        if ($request->has('session')) {
            $session = $request->input('session');

            if ($user->session != $session) {
                Login::create(['time' => date('Y-m-d H:i:s'), 'ip' => $request->ip(), 'user_id' => $user->id, 'valid' => false]);

                return response()->json(['error' => 'invalid-session'], 401);
            }

            if (strtotime($user->session_create_time) < time() - (60 * 60)) {
                Login::create(['time' => date('Y-m-d H:i:s'), 'ip' => $request->ip(), 'user_id' => $user->id, 'valid' => false]);

                return response()->json(['error' => 'session-expired'], 401);
            }
        } else if ($request->has('api-key')) {
            $key = $request->input('api-key');

            $goodKey = false;
            $goodIp = false;

            foreach ($user->apiKeys as $apiKey) {
                if ($key == $apiKey->key) {
                    $goodKey = true;

                    foreach ($apiKey->ips as $ip) {
                        if ($request->ip() == $ip->ip) {
                            $goodIp = true;

                            break 2;
                        }
                    }
                }
            }

            if (!$goodKey) {
                Login::create(['time' => date('Y-m-d H:i:s'), 'ip' => $request->ip(), 'user_id' => $user->id, 'valid' => false]);

                return response()->json(['error' => 'invalid-api-key'], 401);
            }

            if (!$goodIp) {
                Login::create(['time' => date('Y-m-d H:i:s'), 'ip' => $request->ip(), 'user_id' => $user->id, 'valid' => false]);

                return response()->json(['error' => 'invalid-ip'], 401);
            }

            Login::create(['time' => date('Y-m-d H:i:s'), 'ip' => $request->ip(), 'user_id' => $user->id, 'valid' => true]);

        } else {
            return response()->json(['error' => 'no-authentication-provided'], 401);
        }

        return $next($request);
    }

}