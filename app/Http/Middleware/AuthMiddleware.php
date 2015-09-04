<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;

class AuthMiddleware {
//User login logging
    public function handle($request, Closure $next) {


        $user = User::find($request->route()[2]['id']);
/*
        $request->setUserResolver(function() {
            return User::find(Request::route()[2]['id']);
        });

        var_dump($request->user());*/

        if (is_null($user)) {
            return response()->json(['error' => 'User not found'], 401);
        }

        if ($request->has('session')) {
            $session = $request->input('session');

            if ($user->session != $session) {
                return response()->json(['error' => 'Invalid session'], 401);
            }

            if (strtotime($user->session_create_time) < time() - (60 * 60)) {
                return response()->json(['error' => 'Session expired'], 401);
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
                return response()->json(['error' => 'Invalid API key'], 401);
            }

            if (!$goodIp) {
                return response()->json(['error' => 'IP address not valid for API key'], 401);
            }

        } else {
            return response()->json(['error' => 'No authentication provided'], 401);
        }

        return $next($request);
    }

}