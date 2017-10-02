<?php

namespace TrailWarehouse\AppBundle\EventListener;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use TrailWarehouse\AppBundle\Service\{WhatDate, Restocker, RepositoryManager};
use TrailWarehouse\AppBundle\Entity\Action;
use Doctrine\ORM\EntityManagerInterface;

/**
 *
 */
class RestockerListener
{
    /**
     * @var WhatDate
     */
    protected $whatDate;

    /**
     * @var Restocker
     */
    protected $restocker;

    /**
     *
     */
    public function __construct(WhatDate $whatDate, Restocker $restocker, RepositoryManager $rm)
    {
        $this->whatDate = $whatDate;
        $this->restocker = $restocker;
        $this->rm = $rm;
        $this->repo = [
            'action' => $this->rm->get('Action'),
            'product' => $this->rm->get('Product'),
        ];
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        if (NULL === $action['last_restock'] = $this->repo['action']->findNewestActionByName('restock')) {
            $action['last_restock'] = (new Action())->setName('Initialization')->setDate(new \DateTime('1970-01-01'));
        }
        $last_restock_month = (new WhatDate($action['last_restock']->getDate()))->getMonth();
        $actual_month = $this->whatDate->getMonth();

        if ($last_restock_month < $actual_month) {
            $this->restocker->restock($this->repo['product']->findAll(), 30);
        }
    }

}
