<?php

namespace App\Tests;

use App\Entity\Client;

class ClientApiControllerTest extends ApiTestCase
{   
    public function testGetClientsOfUser() {
        $client = static::createClient();

        $jwtToken = $this->authenticateClient($client);

        $client->request('GET', 'http://demoloc.oauth/api/users/17/clients', ["page" => 1, "limit" => 5], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $jwtToken,
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testGetClient() {
        $client = static::createClient();

        $jwtToken = $this->authenticateClient($client);

        $client->request('GET', 'http://demoloc.oauth/api/clients/603', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $jwtToken,
        ]);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testDeleteClient() {
        $client = static::createClient();

        $jwtToken = $this->authenticateClient($client);

        $client->request('DELETE', 'http://demoloc.oauth/api/clients/655', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $jwtToken,
        ]);

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    public function testDeleteSomeoneElsesClient() {
        $client = static::createClient();

        $jwtToken = $this->authenticateClient($client);

        $client->request('DELETE', 'http://demoloc.oauth/api/clients/604', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $jwtToken,
        ]);

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }

    public function testAddClient() {
        $client = static::createClient();

        $jwtToken = $this->authenticateClient($client);

        $jsonClient = json_encode(["firstname" => "John", "lastname" => "Doe", "email" => "john.doe@gmail.com", "phone" => "+33086786303"]);

        $client->request('POST', 'http://demoloc.oauth/api/clients', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $jwtToken,
            'CONTENT_TYPE' => 'application/json'
        ],$jsonClient);

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }


    public function testAddClientError422() {
        $client = static::createClient();

        $jwtToken = $this->authenticateClient($client);

        $jsonClient = json_encode(["lastname" => "Doe", "email" => "john.doe@gmail.com", "phone" => "+33086786303"]);

        $client->request('POST', 'http://demoloc.oauth/api/clients', [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer ' . $jwtToken,
            'CONTENT_TYPE' => 'application/json'
        ],$jsonClient);

        $this->assertEquals(422, $client->getResponse()->getStatusCode());
    }
}
