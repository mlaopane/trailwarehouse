<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use TrailWarehouse\AppBundle\Entity\User;
use TrailWarehouse\AppBundle\Service\UserMailer;

class ContactController extends TrailWarehouseController
{
    public function indexAction()
    {
        return new Response();
    }

    public function sendEmailAction(UserInterface $user, UserMailer $userMailer)
    {
        if (!$user instanceof User) {
            return new Response("You are not logged in !");
        }

        $userMailer->sendWelcome($user);

        $data = [];

        return $this->renderTwig('send_email', $data);
    }

}
