<?php

namespace AppBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TrailWarehouse\AppBundle\Entity\Size;

/**
 *
 */
class LoadSize implements FixtureInterface, OrderedFixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $repository['category'] = $manager->getRepository('TrailWarehouseAppBundle:Category');

    $cat_accessoires = $repository['category']->findOneByName('accessoires');
    $cat_textile = $repository['category']->findOneByName('textile');
    $cat_chaussures = $repository['category']->findOneByName('chaussures');

    $data = [];

    // Accessoires
    $data[] = [
      'value'         => 'U',
      'unit'          => NULL,
      'unit_shortcut' => NULL,
      'category'      => $cat_accessoires,
    ];

    // Textile
    $sizes = [ 'U', 'XS', 'S', 'M', 'L', 'XL' ];
    foreach ($sizes as $size) {
      $data[] = [
        'value'         => $size,
        'unit'          => NULL,
        'unit_shortcut' => NULL,
        'category'      => $cat_textile,
      ];
    }

    // Chaussures
    for ($i = 20 ; $i < 35 ; $i += 0.5) {
      $data[] = [
        'value'         => round($i, 1),
        'unit'          => 'centimeter',
        'unit_shortcut' => 'cm',
        'category'      => $cat_chaussures,
      ];
    }
    foreach ($data as $element) {
      $size = (new Size())
        ->setValue($element['value'])
        ->setUnit($element['unit'])
        ->setUnitShortcut($element['unit_shortcut'])
        ->setCategory($element['category'])
      ;
      $manager->persist($size);
    }
    $manager->flush();
  }

  public function getOrder()
  {
    return 4;
  }
}
