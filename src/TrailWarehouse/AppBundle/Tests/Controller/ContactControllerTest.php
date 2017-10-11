<?php

namespace TrailWarehouse\AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ContactControllerTest extends WebTestCase
{
    public function testSendemail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'envoyer-email');
    }

}
