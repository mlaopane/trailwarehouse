<?php

namespace TrailWarehouse\AppBundle\DataFixtures\ORM;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TrailWarehouse\AppBundle\Entity\Role;

/**
 *
 */
class LoadRole implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $data = [
      [
        'name'  => 'ROLE_USER',
      ],
      [
        'name'  => 'ROLE_ADMIN',
      ],
      [
        'name'  => 'ROLE_SUPER_ADMIN',
      ],
    ];
    foreach ($data as $element) {
      $item = (new Role())
        ->setName($element['name']);
      $manager->persist($item);
    }
    $manager->flush();
  }
}
