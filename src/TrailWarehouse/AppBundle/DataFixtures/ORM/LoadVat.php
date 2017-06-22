<?php

namespace AppBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TrailWarehouse\AppBundle\Entity\Vat;

/**
 *
 */
class LoadVat implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $data = [
      ['country' => 'fr', 'value' => 0.20],
    ];
    foreach ($data as $element) {
      $vat = (new Vat())
        ->setCountry($element['country'])
        ->setValue($element['value'])
      ;
      $manager->persist($vat);
    }
    $manager->flush();
  }
}
