<?php

namespace AppBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TrailWarehouse\AppBundle\Entity\Promo;

/**
 *
 */
class LoadPromo implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $data = [
      [
        'code' => 'WELCOME10',
        'value' => 0.10,
        'start' => new \DateTime('2017-06-05 00:00:00'),
        'end' => new \DateTime('2018-06-05 00:00:00'),
      ],
      [
        'code' => 'SUMMER15',
        'value' => 0.15,
        'start' => new \DateTime('2017-06-19 00:00:00'),
        'end' => new \DateTime('2017-07-04 00:00:00'),
      ],
      [
        'code' => 'AUTUMN12',
        'value' => 0.12,
        'start' => new \DateTime('2017-09-18 00:00:00'),
        'end' => new \DateTime('2017-10-03 00:00:00'),
      ],
    ];
    foreach ($data as $element) {
      $item = (new Promo())
        ->setCode($element['code'])
        ->setValue($element['value'])
        ->setStart($element['start'])
        ->setEnd($element['end'])
      ;
      $manager->persist($item);
    }
    $manager->flush();
  }
}
