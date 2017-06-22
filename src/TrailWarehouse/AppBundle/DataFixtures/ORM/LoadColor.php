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
      ['name' => 'rouge', 'value' => '#ec134f', 'type' => 'solo'],
      ['name' => 'vert', 'value'  => '#26d93d', 'type' => 'solo'],
      ['name' => 'bleu', 'value'  => '#2b8cee', 'type' => 'solo'],
      ['name' => 'jaune', 'value' => '#fde32d', 'type' => 'solo'],
      ['name' => 'blanc', 'value' => '#ffffff', 'type' => 'solo'],
      ['name' => 'gris', 'value'  => '#cdcdcd', 'type' => 'solo'],
      ['name' => 'noir', 'value'  => '#010101', 'type' => 'solo'],
    ];
    foreach ($data as $element) {
      $item = (new Color())
        ->setName($element['name'])
        ->setValue($element['value'])
        ->setType($element['type'])
      ;
      $manager->persist($item);
    }
    $manager->flush();
  }
}
