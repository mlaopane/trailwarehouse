<?php

namespace TrailWarehouse\AppBundle\EventListener;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use TrailWarehouse\AppBundle\Service\{RepositoryManager, UserMailer, ActionManager};

/**
 *
 */
class InteractiveLoginListener
{
    /**
     * @var UserMailer
     */
    protected $userMailer;

    /**
     * @var ActionManager
     */
    protected $am;

    /**
     * @var array
     */
    protected $repo;

    /**
     * InteractiveLoginListener constructor.
     *
     * @param UserMailer $userMailer
     * @param RepositoryManager $rm
     * @param ActionManager $am
     */
    public function __construct(UserMailer $userMailer, RepositoryManager $rm, ActionManager $am)
    {
        $this->userMailer = $userMailer;
        $this->am = $am;
        $this->repo = [
            'action' => $rm->get('Action'),
            'product' => $rm->get('Product'),
        ];
    }

    /**
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();
        $this->am->add('signin')->register();
    }

}
