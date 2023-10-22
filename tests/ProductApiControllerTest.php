<?php

namespace App\Tests;


class ProductApiControllerTest extends ApiTestCase
{
    public function testGetProducts() {
        $client = static::createClient();

        $jwtToken = $this->authenticateClient($client);

        $client->request('GET', 'http://demoloc.oauth/api/products', ["page" => 1, "limit" => 5], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $jwtToken,
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetProduct() {
        $client = static::createClient();

        $jwtToken = $this->authenticateClient($client);

        $client->request('GET', 'http://demoloc.oauth/api/products/193', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $jwtToken,
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }


    public function testGetProductError404() {
        $client = static::createClient();

        $jwtToken = $this->authenticateClient($client);

        $client->request('GET', 'http://demoloc.oauth/api/products/999999999', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $jwtToken,
        ]);

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
