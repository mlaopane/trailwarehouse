<?php

namespace TrailWarehouse\AppBundle\Repository;
use TrailWarehouse\AppBundle\Entity\Brand;
use TrailWarehouse\AppBundle\Entity\Family;
use TrailWarehouse\AppBundle\Entity\Category;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;
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
  protected function getBuilder() {
    return $this->_em->createQueryBuilder($this->entity_name)
      ->addSelect('family')
      ->addSelect('color')
      ->addSelect('image')
      ->innerJoin('family_color.family', 'category')
      ->innerJoin('family_color.color', 'brand')
      ->innerJoin('family_color.image', 'image')
    ;
  }

}