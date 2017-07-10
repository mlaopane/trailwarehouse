<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends Controller
{
    public function indexAction(EntityManagerInterface $em)
    {
      $data['brands'] = $em->getRepository('TrailWarehouseAppBundle:Brand')->findAll(['brand' => 'asc']);
      return $this->render('TrailWarehouseAppBundle:Shop:index.html.twig', $data);
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
