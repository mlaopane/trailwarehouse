<?php

namespace TrailWarehouse\AppBundle\Repository;

use Doctrine\ORM\QueryBuilder;

/**
 * OrderRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrderRepository extends CommonRepository
{
  protected function getBuilder(): QueryBuilder
  {
    return $this->createQueryBuilder($this->getEntityName())
      ->innerJoin($this->getEntityName().'.user', 'user')
      ->leftJoin($this->getEntityName().'.address', 'address')
    ;
  }
}
