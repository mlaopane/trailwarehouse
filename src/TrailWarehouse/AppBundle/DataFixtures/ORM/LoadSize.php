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

    $category = $repository['category']->findOneByName('textile');
    $data = [
      ['value' => 'U', 'unit'  => NULL, 'unit_shortcut' => NULL, 'category' => $category],
      ['value' => 'XS', 'unit' => NULL, 'unit_shortcut' => NULL, 'category' => $category],
      ['value' => 'S', 'unit'  => NULL, 'unit_shortcut' => NULL, 'category' => $category],
      ['value' => 'M', 'unit'  => NULL, 'unit_shortcut' => NULL, 'category' => $category],
      ['value' => 'L', 'unit'  => NULL, 'unit_shortcut' => NULL, 'category' => $category],
      ['value' => 'XL', 'unit' => NULL, 'unit_shortcut' => NULL, 'category' => $category],
    ];

    $category = $repository['category']->findOneByName('chaussures');
    for ($i = 20 ; $i < 35 ; $i += 0.5) {
      $data[] = [
        'value'         => round($i, 1),
        'unit'          => 'centimeter',
        'unit_shortcut' => 'cm',
        'category'      => $category,
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
