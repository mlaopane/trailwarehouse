<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->redirectToRoute('app_shop');
        $data = [];
        return $this->render('TrailWarehouseAppBundle:Home:index.html.twig', $data);
    }

    public function contactAction()
    {
        $data = [];
        return $this->render('TrailWarehouseAppBundle:Home:contact.html.twig', $data);
    }

    public function aboutAction()
    {
        $data = [];
        return $this->render('TrailWarehouseAppBundle:Home:about.html.twig', $data);
    }

    public function mentionsAction()
    {
        $data = [];
        return $this->render('TrailWarehouseAppBundle:Home:mentions.html.twig', $data);
    }
}
