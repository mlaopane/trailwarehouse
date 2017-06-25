<?php

namespace TrailWarehouse\AppBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TrailWarehouse\AppBundle\Entity\Color;

/**
 *
 */
class LoadColor implements FixtureInterface, OrderedFixtureInterface
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

  public function getOrder()
  {
    return 3;
  }
}
