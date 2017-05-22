<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ShopController extends Controller
{

  /**
   * 'app_shop'
   */
  public function indexAction()
  {
    $manager = $this->getDoctrine()->getManager();
    $brands = $manager->getRepository('TrailWarehouseAppBundle:Brand')->findAll(['brand' => 'asc']);
    $data = [
      'brands' => $brands,
    ];
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
   * 'app_shop_category'
   * @param {string} $category
   */
  public function categoryAction($category)
  {
    $category_not_found = true;
    $categories = $this->getDoctrine()->getRepository('TrailWarehouseAppBundle:Category')->findAll();
    foreach ($categories as $key => $db_category) {
      if ($db_category->getName() == $category) {
        $category_not_found = false;
        break;
      }
    }
    if ($category_not_found) {
      return $this->redirectToRoute('app_shop');
    }
    $db_category = $this->getDoctrine()->getRepository('TrailWarehouseAppBundle:Category')
        ->findOneBy(['name' => $category]);
    $db_families = $this->getDoctrine()->getRepository('TrailWarehouseAppBundle:Family')
      ->findAll();
    $data = [
      'category' => $db_category,
      'families' => $db_families,
    ];
    return $this->render('TrailWarehouseAppBundle:Shop:category.html.twig', $data);
  }
}
