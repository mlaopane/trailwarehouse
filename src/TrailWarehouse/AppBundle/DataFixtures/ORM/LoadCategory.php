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
      'textile',
      'électronique',
      'accessoires',
    ];
    foreach ($data as $name) {
      $category = new Category();
      $category->setName($name);
      $manager->persist($category);
    }
    $manager->flush();
  }
}
