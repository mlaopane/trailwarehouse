<?php

namespace TrailWarehouse\AppBundle\Repository;
use Doctrine\ORM\Query;
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
   * Get all Entities
   *
   * @return Entity...
   */
  public function getAll($as_array = true) {
    $query = $this->createQueryBuilder('entity')->getQuery();
    return $as_array ? $query->getArrayResult() : $query->getResult();
  }

  /**
   * Get one Entity
   *
   * @return Array
   */
  public function getOneBy($field, $value, $as_array = true) {
    $query = $this->createQueryBuilder('entity')
      ->where('entity.'.$field.' = :value')
      ->setParameter('value', $value)
      ->setMaxResults(1)
      ->getQuery()
    ;
    return $as_array ? $query->getOneOrNullResult(Query::HYDRATE_ARRAY) : $query->getOneOrNullResult();
  }

  public function get($id, $as_array = true) {
    return $this->getOneBy('id', $id, $as_array);
  }

  /**
   * Get random entities
   *
   * @param int $count : Entities count
   *
   * @return array
   */
  public function getRand($count = 5, $as_array = true) {
    $query = $this->createQueryBuilder('entity')
      ->addSelect('RAND() as HIDDEN rand')
      ->addOrderBy('rand')
      ->setMaxResults($count)
      ->getQuery()
    ;
    return $as_array ? $query->getArrayResult() : $query->getResult();
  }

  /**
   * Get one random entity
   *
   * @return Entity
   */
  public function getOneRand($as_array = true) {
    $query = $this->createQueryBuilder('entity')
      ->addSelect('RAND() as HIDDEN rand')
      ->addOrderBy('rand')
      ->setMaxResults(1)
      ->getQuery()
    ;
    return $as_array ? $query->getSingleResult(Query::HYDRATE_ARRAY) : $query->getSingleResult();
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
      ->getOneOrNullResult(Query::HYDRATE_ARRAY);
  }
}
