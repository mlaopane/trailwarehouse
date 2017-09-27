<?php

namespace TrailWarehouse\AppBundle\EventListeners;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use TrailWarehouse\AppBundle\Services\WhatDate;

/**
 *
 */
class RestockListener
{

    public function onKernelRequest(GetResponseEvent $event, WhatDate $whatDate)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

    }

}
