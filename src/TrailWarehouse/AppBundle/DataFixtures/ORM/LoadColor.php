<?php

namespace AppBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TrailWarehouse\AppBundle\Entity\Color;

/**
 *
 */
class LoadColor implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $data = [
      ['name' => 'rouge', 'value' => '#ec134f'],
      ['name' => 'vert', 'value'  => '#26d93d'],
      ['name' => 'bleu', 'value'  => '#2b8cee'],
      ['name' => 'jaune', 'value' => '#fde32d'],
      ['name' => 'blanc', 'value' => '#ffffff'],
      ['name' => 'gris', 'value'  => '#cdcdcd'],
      ['name' => 'noir', 'value'  => '#010101'],
    ];
    foreach ($data as $element) {
      $item = (new Color())
        ->setName($element['name'])
        ->setValue($element['value'])
      ;
      $manager->persist($item);
    }
    $manager->flush();
  }
}
