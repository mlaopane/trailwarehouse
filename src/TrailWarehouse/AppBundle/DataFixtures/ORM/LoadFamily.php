<?php

namespace TrailWarehouse\AppBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TrailWarehouse\AppBundle\Entity\Family;

/**
 *
 */
class LoadFamily implements FixtureInterface, OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $brand_repository    = $manager->getRepository('TrailWarehouseAppBundle:Brand');
    $category_repository = $manager->getRepository('TrailWarehouseAppBundle:Category');
    $brands              = $brand_repository->findAll();
    $categories          = $category_repository->findAll();

    foreach ($categories as $category) {
      foreach ($brands as $brand) {
        $family = new Family();
        $family->setBrand($brand);
        $family->setCategory($category);
        switch ($category->getName()) {
          case 'chaussures':
            if ($brand->getName() == 'merrell') {
              $family->setName('Chaussures de trail minimalistes');
            }
            else {
              $family->setName('Chaussures de trail');
            }
            $manager->persist($family);
            break;
          case 'textile':
            if ($brand->getName() != 'vibram') {
              $family->setName('T-Shirt technique');
              $manager->persist($family);
            }
            break;
          case 'accessoires':
            switch ($brand->getName()) {
              case 'merrel':
                $family->setName('Bâtons de marche');
                $manager->persist($family);
                break;
              case 'salomon':
                $family->setName('Sac d\'hydratation');
                $manager->persist($family);
                break;
              default:
                break;
            }
            break;
          default:
            break;
        }
      }
      $manager->flush();
    }
  }

  public function getOrder()
  {
    return 5;
  }
}
