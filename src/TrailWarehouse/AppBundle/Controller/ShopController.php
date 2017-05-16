<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShopController extends Controller
{

  /**
   *
   */
  public function indexAction()
  {
    $data = [];
    return $this->render('TrailWarehouseAppBundle:Shop:index.html.twig', $data);
  }

  /**
   * Gets the categories then render the menu
   */
  public function menuAction()
  {
    $categories = $this->getDoctrine()->getRepository('TrailWarehouseAppBundle:Category')->findAll();
    $data = [
      'categories' => $categories,
    ];
    return $this->render('TrailWarehouseAppBundle:Shop:categories.html.twig', $data);
  }

  /**
   * @param {string} $category
   */
  public function categoryAction($category)
  {
    $data['category'] = $category;

    $products = $this->getDoctrine()->getRepository('TrailWarehouseAppBundle:Product')->findAll();
    $data['products'] = $products;

    return $this->render('TrailWarehouseAppBundle:Shop:category.html.twig', $data);
  }
}
