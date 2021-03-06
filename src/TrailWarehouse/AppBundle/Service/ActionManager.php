<?php

namespace TrailWarehouse\AppBundle\Service;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use TrailWarehouse\AppBundle\Service\{RepositoryManager};
use TrailWarehouse\AppBundle\Entity\{Action};

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
     * @var ArrayCollection
     */
    private $actions;


    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
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
     * @return ActionManager
     */
    public function add(string $name): ActionManager
    {
        $this->actions[] = new Action($name);

        return $this;
    }

    /**
     * @param void
     * @return ActionManager
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
