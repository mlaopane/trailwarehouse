<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        $data = [];
        return $this->render('TrailWarehouseAppBundle:Home:index.html.twig', $data);
    }
}
