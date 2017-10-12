<?php

namespace TrailWarehouse\AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestControllerTest extends WebTestCase
{
    public function testWelcome()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/test/welcome');
    }

    public function testPassword()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/test/password');
    }

}
