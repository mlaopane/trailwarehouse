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
     * @param string entityName (ucfirst + lcase)
     * @return EntityRepository
     */
    public function get(string $entityName): EntityRepository
    {
        return $this->em->getRepository('TrailWarehouseAppBundle:' . $entityName);
    }
}
