<?php

namespace TrailWarehouse\AppBundle\Tests;

use TrailWarehouse\AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserMailerTest extends WebTestCase
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var UserMailer
     */
    private $userMailer;

    public function __construct()
    {
        parent::__construct();
        $this->container = static::createClient()->getContainer();
        $this->userMailer = $this->container->get('trail_warehouse.user_mailer');
    }
    
}
