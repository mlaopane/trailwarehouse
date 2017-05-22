<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ErrorController extends Controller
{
    public function indexAction()
    {
        $data = [];
        return $this->render('TrailWarehouseAppBundle:Error:index.html.twig', $data);
    }
}
