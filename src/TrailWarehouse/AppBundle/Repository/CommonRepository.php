<?php

namespace TrailWarehouse\AppBundle\Repository;
use Doctrine\ORM\EntityRepository;

/**
 * The CommonRepository is just an intermediate class between
 * the EntityRepository and the final Repository
 *
 * This class is designed for inheritance and users can subclass this class to
 * write their own repositories with business-specific methods to locate entities.
 *
 * @author  Mickael LAO-PANE <mlaopane@gmail.com>
 */
class CommonRepository extends EntityRepository
{

  /**
   * Get random entities
   *
   * @param int $count : Entities count
   *
   * @return array
   */
  public function getRand($count = 5) {
    return $this->createQueryBuilder('entity')
      ->addSelect('RAND() as HIDDEN rand')
      ->addOrderBy('rand')
      ->setMaxResults($count)
      ->getQuery()
      ->getResult();
  }

  /**
   * Get one random entity
   *
   * @return Entity
   */
  public function getOneRand() {
    return $this->createQueryBuilder('entity')
      ->addSelect('RAND() as HIDDEN rand')
      ->addOrderBy('rand')
      ->setMaxResults(1)
      ->getQuery()
      ->getOneOrNullResult();
  }

  /**
   * Get one random entity by
   *
   * @return Entity
   */
  public function getOneRandBy($field, $value) {
    return $this->createQueryBuilder('entity')
      ->addSelect('RAND() as HIDDEN rand')
      ->where('entity.'.$field.' = :value')
      ->setParameter('value', $value)
      ->addOrderBy('rand')
      ->setMaxResults(1)
      ->getQuery()
      ->getOneOrNullResult();
  }
}
