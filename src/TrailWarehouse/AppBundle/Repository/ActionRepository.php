<?php

namespace TrailWarehouse\AppBundle\Repository;
use Doctrine\ORM\QueryBuilder;
use TrailWarehouse\AppBundle\Entity\Action;

/**
 * ActionRepository
 *
 */
class ActionRepository extends CommonRepository
{
    /**
     * Find the most recent action specified
     */
    public function findNewestActionByName(string $name): ?Action
    {
        return $this->getBuilderByNameAndSort($name, 'DESC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Find the oldest action specified
     */
    public function findOldestActionByName(string $name): ?Action
    {
        return $this->getBuilderByNameAndSort($name, 'ASC')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Add name & sort filters to the Query Builder
     */
    private function getBuilderByNameAndSort(string $name, string $sort = 'ASC'): QueryBuilder
    {
        return $this->getBuilder()
            ->where($this->getEntityName().'.name = :name')
            ->setParameter('name', $name, 'string')
            ->orderBy($this->getEntityName().'.date', $sort)
            ->setMaxResults(1);
    }
}
