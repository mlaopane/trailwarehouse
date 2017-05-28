<?php

namespace TrailWarehouse\AppBundle\Services;
use Doctrine\ORM\EntityManager;

/**
 *
 */
class TWRepository
{

  private $manager;
  private $tw_string;

  public function __construct(EntityManager $manager, TWString $tw_string) {
    $this->manager = $manager;
    $this->tw_string = $tw_string;
  }

  /**
   *
   */
  public function getRepository($str) {
    return $this->manager->getRepository('TrailWarehouseAppBundle:'.$this->tw_string->lowercase($str));
  }

}
