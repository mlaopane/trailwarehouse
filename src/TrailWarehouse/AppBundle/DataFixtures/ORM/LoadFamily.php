<?php

namespace AppBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TrailWarehouse\AppBundle\Entity\Brand;
use TrailWarehouse\AppBundle\Entity\Family;
use TrailWarehouse\AppBundle\Entity\Category;

/**
 *
 */
class LoadFamily implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $brand_repository = $manager->getRepository('TrailWarehouseAppBundle:Brand');
    $brands = $brand_repository->findAll();
    $category_repository = $manager->getRepository('TrailWarehouseAppBundle:Category');
    $categories = $category_repository->findAll();
    foreach ($categories as $category) {
      foreach ($brands as $brand) {
        $item = new Family();
        $item->setBrand($brand);
        $item->setCategory($category);
        switch ($category->getName()) {
          case 'chaussures':
            $item->setName('Chaussures de trail');
            $manager->persist($item);
            break;
          case 'électronique':
            $item->setName('Montre Cardio-GPS');
            $manager->persist($item);
            break;
          case 'vêtements':
            $item->setName('T-Shirt technique');
            $manager->persist($item);
            break;
          case 'accessoires':
            $item->setName('Bâtons de marche');
            $manager->persist($item);
            break;
          default:
            break;
        }
      }
      $manager->flush();
    }
  }
}
