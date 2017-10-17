<?php

namespace TrailWarehouse\AppBundle\Service;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 *
 *
 * @author Mickaël LAO-PANE
 */
class RepositoryManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @param EntityManagerInterface em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Return an Entity Repository from the TrailWarehouse\AppBundle
     * @param  string           $entityName (Capitalize)
     * @return EntityRepository             [description]
     */
    public function get(string $entityName): EntityRepository
    {
        return $this->em->getRepository('TrailWarehouseAppBundle:' . $entityName);
    }

    /**
     * [getMultiple description]
     * @param  string... $entityNames (Capitalize)
     * @return EntityRepository[]     [description]
     */
    public function getMultiple(string... $entityNames): array
    {
        foreach ($entityNames as $entityName) {
            $repo[] = $this->em->getRepository('TrailWarehouseAppBundle:' . $entityName);
        }
        return $repo;
    }
}
