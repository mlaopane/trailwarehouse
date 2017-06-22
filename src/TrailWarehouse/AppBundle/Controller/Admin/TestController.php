<?php

namespace TrailWarehouse\AppBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TrailWarehouse\AppBundle\Entity\Product;
use TrailWarehouse\AppBundle\Entity\Family;
use TrailWarehouse\AppBundle\Entity\Color;
use TrailWarehouse\AppBundle\Entity\Size;

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
          if ( ($size = $repository['size']->getOneRandBy('category', $family->getCategory(), false)) == NULL) {
            $size = NULL;
          }
          if ( ($color = $repository['color']->getOneRand(false)) == NULL) {
            $color = NULL;
          }
          $price = ($family->getCategory()->getName() == 'accessoires') ? (mt_rand(1, 7) * 10) : (mt_rand(2, 15) * 10);
          $stock = mt_rand(0, 6) * 5;
          $product = (new Product())
            ->setFamily($family)
            ->setColor($color)
            ->setSize($size)
            ->setPrice($price)
            ->setStock($stock)
          ;
          $manager->persist($product);
        }
        $manager->flush();
        return $this->redirectToRoute('app_shop_categories', $data);
    }

}
