<?php

namespace TrailWarehouse\AppBundle\EventListener;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use TrailWarehouse\AppBundle\Service\{WhatDate, Restocker, RepositoryManager};
use TrailWarehouse\AppBundle\Entity\Action;
use TrailWarehouse\AppBundle\Service\ActionManager;
use Doctrine\ORM\EntityManagerInterface;

/**
 *
 */
class RestockListener
{
    /**
     * @var Restocker
     */
    protected $restocker;

    /**
     * @var ActioManager
     */
    protected $actionM;

    /**
     * @var array
     */
    protected $repo;

    /**
     *
     */
    public function __construct(Restocker $restocker, RepositoryManager $rm, ActionManager $actionM)
    {
        $this->restocker = $restocker;
        $this->actionM = $actionM;
        $this->repo = [
            'action' => $rm->get('Action'),
            'product' => $rm->get('Product'),
        ];

    }

    /**
     * @param GetResponseEvent event
     * @return void
     */
    public function onKernelRequest(GetResponseEvent $event): void
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $lastRestock = $this->repo['action']->findNewestActionByName('restock');

        if ($this->restockNeedsUpdate($lastRestock)) {
            $this->restocker->restock($this->repo['product']->findAll(), 30);
            $this->actionM->add('restock')->register();
        }
    }

    /**
     * @param Action lastRestock
     * @return bool
     */
    private function restockNeedsUpdate(?Action $lastRestock): bool
    {
        if ($lastRestock === NULL) {
            return true;
        }

        $previous = new WhatDate($lastRestock->getDate());
        $current = new WhatDate();

        return
            $previous['year'] < $current['year'] ||
            $previous['month'] < $current['month'];
    }

}
