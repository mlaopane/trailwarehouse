<?php

namespace AppBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TrailWarehouse\AppBundle\Entity\Brand;

/**
 *
 */
class LoadBrand implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $data = [
      'merrell',
      'new balance',
      'salomon',
      'vibram',
    ];
    foreach ($data as $item_name) {
      $item = new Brand();
      $item->setName($item_name);
      $item->setLogo('images/no_picture.png');
      $manager->persist($item);
    }
    $manager->flush();
  }
}
