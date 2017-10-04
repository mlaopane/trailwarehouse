<?php

namespace TrailWarehouse\AppBundle\Repository;
use TrailWarehouse\AppBundle\Entity\Brand;
use TrailWarehouse\AppBundle\Entity\Family;
use TrailWarehouse\AppBundle\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 * FamilyRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FamilyColorRepository extends CommonRepository
{
  /* ----- Private Methods ----- */
  protected function getBuilder(): QueryBuilder
  {
    return $this->_em->createQueryBuilder($this->entity_name)
      ->addSelect('family')
      ->addSelect('color')
      ->addSelect('image')
      ->innerJoin($this->entity_name.'.family', 'category')
      ->innerJoin($this->entity_name.'.color', 'brand')
      ->innerJoin($this->entity_name.'.image', 'image')
    ;
  }

}
