<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ClientController extends Controller
{
    public function signupAction()
    {
        return $this->render('TrailWarehouseAppBundle:Client:signup.html.twig', array(
            // ...
        ));
    }

    public function signinAction()
    {
        return $this->render('TrailWarehouseAppBundle:Client:signin.html.twig', array(
            // ...
        ));
    }

    public function signoutAction()
    {
        return $this->render('TrailWarehouseAppBundle:Client:signout.html.twig', array(
            // ...
        ));
    }

}
