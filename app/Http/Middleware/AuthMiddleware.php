<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\Login;
use Closure;

class AuthMiddleware {

    public function handle($request, Closure $next) {
        $ipCheck = Login::where(['ip' => $request->ip(), 'valid' => false])->where('time', '>', date('Y-m-d H:i:s', time() - (60 * 60 * 24)))->get();

        if (count($ipCheck) > 15) {
            Login::create(['time' => date('Y-m-d H:i:s'), 'ip' => $request->ip(), 'valid' => false]);

            return response()->json(['errors' => ['ip-banned']], 403);
        }

        $user = User::find($request->route()[2]['id']);

        if (is_null($user)) {
            return response()->json(['errors' => ['user-not-found']], 404);
        }

        $userCheck = Login::where(['user_id' => $user->id, 'valid' => false])->where('time', '>', time() - (60 * 15))->get();

        if (count($userCheck) > 15) {
            return response()->json(['errors' => ['user-locked']], 403);
        }

        if ($request->has('session')) {
            $session = $request->input('session');

            if ($user->session != $session) {
                Login::create(['time' => date('Y-m-d H:i:s'), 'ip' => $request->ip(), 'user_id' => $user->id, 'valid' => false]);

                return response()->json(['errors' => ['invalid-session']], 403);
            }

            if (strtotime($user->session_expire_time) < time()) {
                Login::create(['time' => date('Y-m-d H:i:s'), 'ip' => $request->ip(), 'user_id' => $user->id, 'valid' => false]);

                return response()->json(['errors' => ['session-expired']], 403);
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

                            //TODO: Check if key has ability to perform action / access route
                            break 2;
                        }
                    }
                }
            }

            if (!$goodKey) {
                Login::create(['time' => date('Y-m-d H:i:s'), 'ip' => $request->ip(), 'user_id' => $user->id, 'valid' => false]);

                return response()->json(['errors' => ['invalid-api-key']], 403);
            }

            if (!$goodIp) {
                Login::create(['time' => date('Y-m-d H:i:s'), 'ip' => $request->ip(), 'user_id' => $user->id, 'valid' => false]);

                return response()->json(['errors' => ['invalid-ip']], 403);
            }

            Login::create(['time' => date('Y-m-d H:i:s'), 'ip' => $request->ip(), 'user_id' => $user->id, 'valid' => true]);

        } else {
            return response()->json(['errors' => ['no-authentication-provided']], 401);
        }

        $request->setUserResolver(function() use ($user) {
            return $user;
        });

        return $next($request);
    }

}