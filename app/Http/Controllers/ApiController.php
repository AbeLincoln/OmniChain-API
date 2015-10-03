<?php

namespace App\Http\Controllers;

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