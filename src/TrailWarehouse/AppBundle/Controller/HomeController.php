<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('TrailWarehouseAppBundle:Home:index.html.twig', array(
            // ...
        ));
    }

}
