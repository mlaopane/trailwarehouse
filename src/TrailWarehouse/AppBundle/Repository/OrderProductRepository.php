<?php

namespace TrailWarehouse\AppBundle\Repository;
use Doctrine\ORM\QueryBuilder;

/**
 * OrderProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class OrderProductRepository extends CommonRepository
{
  protected function getBuilder(): QueryBuilder
  {
    return $this->createQueryBuilder($this->getEntityName())
      ->addSelect('product')
      ->innerJoin($this->entity_name.'.product', 'product')
    ;
  }
}
