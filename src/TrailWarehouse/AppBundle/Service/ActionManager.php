<?php

namespace TrailWarehouse\AppBundle\Service;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use TrailWarehouse\AppBundle\Service\WhatDate;
use TrailWarehouse\AppBundle\Service\ActionManager;
use TrailWarehouse\AppBundle\Service\RepositoryManager;
use TrailWarehouse\AppBundle\Entity\Action;

/**
 *
 */
class ActionManager
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var RepositoryManager
     */
    private $rm;

    /**
     * @var WhatDate
     */
    private $whatDate;

    /**
     * @var ArrayCollection
     */
    private $actions;


    public function __construct(EntityManagerInterface $em, WhatDate $whatDate, RepositoryManager $rm)
    {
        $this->em = $em;
        $this->rm = $rm;
        $this->whatDate = $whatDate;
        $this->actions = new ArrayCollection();
    }

    /**
     * @param string name
     * @return Action
     */
    public function create(string $name): Action
    {
        $newAction = new Action($name);

        return $newAction;
    }

    /**
     * @param string name
     * @return this
     */
    public function add(string $name): ActionManager
    {
        $this->actions[] = new Action($name);

        return $this;
    }

    /**
     * @param void
     * @return this
     */
    public function register(): ActionManager
    {
        if (!empty($this->actions)) {
            foreach ($this->actions as $action) {
                $this->em->persist($action);
            }
            $this->em->flush();
        }

        return $this;
    }

}
