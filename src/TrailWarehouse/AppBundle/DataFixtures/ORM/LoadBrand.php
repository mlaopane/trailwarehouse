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
    foreach ($data as $name) {
      $brand = new Brand();
      $logo = str_replace([" ", "'"], "images/".$item_name.".png");
      $brand->setName($name);
      $brand->setLogo($logo);
      $manager->persist($brand);
    }
    $manager->flush();
  }
}
