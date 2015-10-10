<?php

class ApiTest extends TestCase {

    public function testAddressEndpoint() {
        $response = $this->call('GET', '/v0/addresses/');
    }

}
