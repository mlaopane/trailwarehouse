<?php

namespace AppBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TrailWarehouse\AppBundle\Entity\Rate;

/**
 *
 */
class LoadRate implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $data = [
      ['country' => 'fr', 'vat' => 0.20],
    ];
    foreach ($data as $element) {
      $item = (new Rate())
        ->setCountry($element['country'])
        ->setVat($element['vat'])
      ;
      $manager->persist($item);
    }
    $manager->flush();
  }
}
