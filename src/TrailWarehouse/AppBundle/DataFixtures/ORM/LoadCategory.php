<?php

namespace AppBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TrailWarehouse\AppBundle\Entity\Category;

/**
 *
 */
class LoadCategory implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $data = [
      'chaussures',
      'vêtements',
      'électronique',
      'accessoires',
      'fake category',
    ];
    foreach ($data as $item_name) {
      $item = new Category();
      $item->setName($item_name);
      $manager->persist($item);
    }
    $manager->flush();
  }
}
