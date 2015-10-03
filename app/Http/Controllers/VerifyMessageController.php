<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VerifyMessageController extends DaemonController {

    public function index(Request $request) {
        $verifyMessage = sendRpcCommand($this->client, 'verifymessage', [$request->input('address', ''), $request->input('signature', ''), $request->input('message', '')]);

        return $this->respond(['verified' => (boolean) $verifyMessage]);
    }

}