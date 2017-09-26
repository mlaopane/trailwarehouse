<?php

namespace TrailWarehouse\AppBundle\EventListeners;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 *
 */
class StockListener
{

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }
        $today = new DateTimeImmutable('now', 'Europe/Paris');
        $today_date = getDate($today->getTimestamp());
        dump($today_date);
        die();
    }

}
