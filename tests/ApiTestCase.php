<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ApiTestCase extends WebTestCase
{
    protected function authenticateClient($client) {
        $credentials = [
            'username' => 'orange@telecom.fr',
            'password' => 'passwordOrange',
        ];
        
        $client->request('POST', 'http://demoloc.oauth/api/login_check', [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($credentials));
        
        $data = json_decode($client->getResponse()->getContent(), true);
        
        return $data['token'] ?? '';
    }
}
