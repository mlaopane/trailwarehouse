<?php

namespace TrailWarehouse\AppBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TrailWarehouse\AppBundle\Entity\Category;

/**
 *
 */
class LoadCategory implements FixtureInterface, OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $data = [
      'chaussures',
      'textile',
      'accessoires',
    ];
    foreach ($data as $name) {
      $category = new Category();
      $category->setName($name);
      $manager->persist($category);
    }
    $manager->flush();
  }

  public function getOrder()
  {
    return 2;
  }
}
