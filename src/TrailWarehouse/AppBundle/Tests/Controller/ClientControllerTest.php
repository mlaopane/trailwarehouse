<?php

namespace TrailWarehouse\AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClientControllerTest extends WebTestCase
{
    public function testSignup()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/signup');
    }

    public function testSignin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/signin');
    }

    public function testSignout()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/signout');
    }

}
