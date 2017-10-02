<?php

namespace TrailWarehouse\AppBundle\Repository;

/**
 * ActionRepository
 *
 */
class ActionRepository extends CommonRepository
{
    public function findNewestActionByName(string $name)
    {
        return $this->getBuilderByNameAndSort($name, 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findOldestActionByName()
    {
        return $this->getBuilderByNameAndSort($name, 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    private function getBuilderByNameAndSort(string $name, string $sort = 'ASC')
    {
        return $this->getBuilder()
            ->where($this->getEntityName().'.name = :name')
            ->setParameter('name', $name, 'string')
            ->orderBy($this->getEntityName().'.date', $sort)
            ->setMaxResults(1)
        ;
    }
}
