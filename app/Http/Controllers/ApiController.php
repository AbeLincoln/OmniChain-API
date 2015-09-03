<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response as IlluminateResponse;

class ApiController extends Controller {

    protected $statusCode = IlluminateResponse::HTTP_OK;

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