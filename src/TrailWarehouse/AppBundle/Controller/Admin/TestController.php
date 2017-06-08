<?php

namespace TrailWarehouse\AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TrailWarehouse\AppBundle\Entity\Product;

class TestController extends Controller
{
    public function indexAction()
    {
        $data = [];
        return $this->render('TrailWarehouseAppBundle:Home:index.html.twig', $data);
    }

    public function addAction()
    {
        $data = [];
        $manager = $this->getDoctrine()->getManager();
        $repository = [
          'family' => $manager->getRepository('TrailWarehouseAppBundle:Family'),
          'color'  => $manager->getRepository('TrailWarehouseAppBundle:Color'),
          'size'   => $manager->getRepository('TrailWarehouseAppBundle:Size'),
        ];

        $families = $repository['family']->findAll();

        foreach ($families as $family) {
          $item = (new Product())
            ->setFamily($family)
            ->setColor($repository['color']->getOneRand(false))
            ->setSize($repository['size']->getOneRand(false))
            ->setPrice(mt_rand(2, 20) * 10)
            ->setStock(mt_rand(1, 15) * 10)
          ;
          $manager->persist($item);
        }
        $manager->flush();
        return $this->render('TrailWarehouseAppBundle:Home:index.html.twig', $data);
    }

}
