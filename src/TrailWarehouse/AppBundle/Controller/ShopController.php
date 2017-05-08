<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Product;
use AppBundle\Entity\Category;

class ShopController extends Controller
{
  public function indexAction()
  {
    $categories = $this->getDoctrine()->getRepository('TrailWarehouseAppBundle:Category')->findAll();
    $data = [
      'categories' => $categories,
    ];
    return $this->render('TrailWarehouseAppBundle:Shop:index.html.twig', $data);
  }

}
