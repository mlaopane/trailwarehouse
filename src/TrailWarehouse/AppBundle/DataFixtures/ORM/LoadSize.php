<?php

namespace AppBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TrailWarehouse\AppBundle\Entity\Size;

/**
 *
 */
class LoadSize implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $data = [
      ['value' => 'U', 'unit' => NULL, 'unit_shortcut' => NULL],
      ['value' => 'XS', 'unit' => NULL, 'unit_shortcut' => NULL],
      ['value' => 'S', 'unit' => NULL, 'unit_shortcut' => NULL],
      ['value' => 'M', 'unit' => NULL, 'unit_shortcut' => NULL],
      ['value' => 'L', 'unit' => NULL, 'unit_shortcut' => NULL],
      ['value' => 'XL', 'unit' => NULL, 'unit_shortcut' => NULL],
    ];
    for ($i = 20 ; $i < 40 ; $i += 0.5) {
      $data[] = [
        'value' => round($i, 1),
        'unit' => 'centimeter',
        'unit_shortcut' => 'cm',
      ];
      // $data[] = [
      //   'value' => round($i * 0.393701, 1),
      //   'unit' => 'inch',
      //   'unit_shortcut' => 'in',
      // ];
    }
    foreach ($data as $element) {
      $item = (new Size())
        ->setValue($element['value'])
        ->setUnit($element['unit'])
        ->setUnitShortcut($element['unit_shortcut'])
      ;
      $manager->persist($item);
    }
    $manager->flush();
  }
}