<?php

namespace TrailWarehouse\AppBundle\Repository;

use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityRepository;

/**
 * The CommonRepository is an intermediate class between
 * the EntityRepository and the inherited Repository
 *
 * This class is designed for inheritance and users can subclass this class to
 * write their own repositories with business-specific methods to locate entities.
 *
 * @author  Mickael LAO-PANE <mlaopane@gmail.com>
 */
abstract class CommonRepository extends EntityRepository
{
    /**
     * [protected description]
     * @var string
     */
    protected $entity_name;

    /**
     * Used for the entity alias
     * @var string
     */
    private $prefix = 'trail_warehouse';

    /**
     * A concatenation of the prefix and the entity class name
     * @var string
     */
    private $alias;

    public function __construct($em, \Doctrine\ORM\Mapping\ClassMetadata $class)
    {
        parent::__construct($em, $class);
        $this->entity_name = 'trail_warehouse_'.lcfirst(str_replace([__NAMESPACE__, '\\', 'Repository'], '', static::class));
        $this->alias = $this->prefix.lcfirst(str_replace([__NAMESPACE__, '\\', 'Repository'], '', static::class));
    }

    /**
     * Gets the entity alias
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * Get all the entities
     * @param  boolean $as_array [description]
     * @return mixed
     */
    public function getAll($as_array = true)
    {
        $query = $this->getBuilder()->getQuery();
        return $as_array ? $query->getArrayResult() : $query->getResult();
    }

    /**
     * Get one Entity by id
     * @param  int     $id       [description]
     * @param  boolean $as_array [description]
     * @return [type]            [description]
     */
    public function getOne(int $id, $as_array = true)
    {
        return $this->getOneBy('id', $id, $as_array);
    }

    /**
     * Get one Entity by field
     * @param  string  $field    [description]
     * @param  mixed   $value    [description]
     * @param  boolean $as_array [description]
     * @return [type]            [description]
     */
    public function getOneBy(string $field, mixed $value, $as_array = true)
    {
        $query = $this->getBuilder()
          ->where($this->entity_name.'.'.$field.' = :value')
          ->setParameter('value', $value)
          ->setMaxResults(1)
          ->getQuery()
        ;
        return $as_array ? $query->getOneOrNullResult(Query::HYDRATE_ARRAY) : $query->getOneOrNullResult();
    }

    /**
    * Get Entities by field
    *
    * @return Array
    */
    public function getBy($field, $value, $as_array = true)
    {
        $query = $this->getBuilder()
          ->where($this->entity_name.'.'. $field .' = :'. $field)
          ->setParameter($field, $value)
          ->getQuery()
        ;
        return $as_array ? $query->getArrayResult() : $query->getResult();
    }

    /**
     * Get Entities by array of fields
     * @param  array   $parameters
     * @param  boolean $as_array  
     * @return [type]             
     */
    public function getByArray(array $parameters, $as_array = true)
    {
        $builder = $this->getBuilder();
        foreach ($parameters as $field => $value) {
            $builder
                ->andWhere($this->entity_name.'.'. $field .' = :'. $field)
                ->setParameter($field, $value)
            ;
        }
        return $as_array ? $builder->getQuery()->getArrayResult() : $builder->getQuery()->getResult();
    }

    /**
    * Get one Entity by array
    *
    * @return Entity
    */
    public function getOneByArray(array $parameters, $as_array = true)
    {
        $builder = $this->getBuilder();
        foreach ($parameters as $field => $value) {
            $condition = $this->entity_name.'.'.$field.' = :'.$field;
            $builder->andWhere($condition)->setParameter($field, $value);
        }
        $query = $builder->setMaxResults(1)->getQuery();
        return $as_array ? $query->getOneOrNullResult(Query::HYDRATE_ARRAY) : $query->getOneOrNullResult();
    }

    /**
    * Get random entities
    *
    * @param int $count : Entities count
    *
    * @return array
    */
    public function getRand($count = 5, $as_array = true)
    {
        $query = $this->getBuilder()
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
    public function getOneRand($as_array = true)
    {
        $query = $this->createQueryBuilder('entity')
          ->addSelect('RAND() as HIDDEN rand')
          ->addOrderBy('rand')
          ->setMaxResults(1)
          ->getQuery()
        ;
        return $as_array ? $query->getOneOrNullResult(Query::HYDRATE_ARRAY) : $query->getOneOrNullResult();
    }

    /**
    * Get one random entity by
    *
    * @return Entity
    */
    public function getOneRandBy($field, $value, $as_array = true)
    {
        $query = $this->createQueryBuilder('entity')
            ->addSelect('RAND() as HIDDEN rand')
            ->where($this->entity_name.'.'.$field.' = :value')
            ->setParameter('value', $value)
            ->addOrderBy('rand')
            ->setMaxResults(1)
            ->getQuery()
        ;
        return $as_array ? $query->getOneOrNullResult(Query::HYDRATE_ARRAY) : $query->getOneOrNullResult();
    }

    /**
    * Get the Entity Name related to the actual Repository
    *
    * @return string
    */
    protected function getEntityName(): string
    {
        return $this->entity_name;
    }

    /**
    * Get the Query Builder with the entity name
    *
    * @return QueryBuilder
    */
    protected function getBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder($this->entity_name);
    }
}
