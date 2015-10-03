<?php

namespace App\Http\Controllers;

use Nbobtc\Command\Command;
use Nbobtc\Http\Client;


class DaemonController extends ApiController {

    protected $client;

    public function __construct() {
        $this->client = new Client('http://' . env('OMCD_ABE_USERNAME', '') . ':' . env('OMCD_ABE_PASSWORD', '') . '@' . env('OMCD_ABE_HOST', '') . ':' . env('OMCD_ABE_PORT', ''));
    }

}