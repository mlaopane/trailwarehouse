<?php

namespace TrailWarehouse\AppBundle\EventListener;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use TrailWarehouse\AppBundle\Service\WhatDate;
use TrailWarehouse\AppBundle\Service\Restocker;

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
    public function __construct(WhatDate $whatDate, Restocker $restocker)
    {
        $this->whatDate = $whatDate;
        $this->restocker = $restocker;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }
    }

}
