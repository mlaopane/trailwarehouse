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
          $product = (new Product())
            ->setFamily($family)
            ->setColor($repository['color']->getOneRand(false))
            ->setSize($repository['size']->getOneRandBy('category', $family->getCategory(), false))
            ->setPrice($family->getCategory()->getName() == 'accessoires' ? mt_rand(1, 7) * 10 : mt_rand(2, 15) * 10)
            ->setStock(mt_rand(0, 5) * 10)
          ;
          $manager->persist($product);
        }
        $manager->flush();
        return $this->redirectToRoute('app_shop_categories', $data);
    }

}
