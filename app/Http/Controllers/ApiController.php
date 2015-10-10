<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller;

class ApiController extends Controller {

    protected $statusCode = 200;

    public function getStatusCode() {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode) {
        $this->statusCode = $statusCode;

        return $this;
    }

    public function respond($data) {
        return response()->json($data, $this->getStatusCode());
    }

}