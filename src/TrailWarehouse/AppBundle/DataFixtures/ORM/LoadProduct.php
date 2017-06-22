<?php

namespace AppBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TrailWarehouse\AppBundle\Entity\Product;

/**
 *
 */
class LoadProduct implements FixtureInterface, OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {

  }

  public function getOrder()
  {
    return 6;
  }
}
