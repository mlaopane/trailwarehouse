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
  public function menuAction($active_category = NULL)
  {
    $categories = $this->getDoctrine()->getRepository('TrailWarehouseAppBundle:Category')->findAll();
    $data = [
      'categories' => $categories,
      'active_category' => $active_category,
    ];
    return $this->render('TrailWarehouseAppBundle:Shop:categories.html.twig', $data);
  }

  /**
   * 'app_shop_category'
   * @param {string} $category
   */
  public function categoryAction($category)
  {
    $doctrine = $this->getDoctrine();
    if ($category == 'toutes') {
      $db_category['name'] = 'toutes';
      $db_families = $doctrine->getRepository('TrailWarehouseAppBundle:Family')->findAll();
    }
    else {
      $category_not_found = true;
      $db_categories = $doctrine->getRepository('TrailWarehouseAppBundle:Category')->findAll();
      foreach ($db_categories as $key => $db_category) {
        if ($db_category->getName() == $category) {
          $category_not_found = false;
          break;
        }
      }
      if ($category_not_found) {
        return $this->redirectToRoute('app_shop');
      }
      $db_category = $doctrine->getRepository('TrailWarehouseAppBundle:Category')
        ->findOneBy(['name' => $category]);
      $db_families = $doctrine->getRepository('TrailWarehouseAppBundle:Family')
        ->findByCategories([$db_category]);
    }
    $data = [
      'active_category' => $db_category,
      'families' => $db_families,
    ];
    return $this->render('TrailWarehouseAppBundle:Shop:category.html.twig', $data);
  }

  /**
   * 'app_shop_family'
   * @param {string} $family
   */
  public function familyAction($family)
  {
    $doctrine = $this->getDoctrine();
    $family_not_found = true;
    $db_families = $doctrine->getRepository('TrailWarehouseAppBundle:Family')->findAll();
    foreach ($db_families as $loop_index => $db_family) {
      $dashed_family = str_replace('\'', '-', str_replace(' ', '-', $db_family->getName()));
      $dashed_family = mb_strtolower($dashed_family, 'UTF-8');
      if ($dashed_family == $family) {
        $family_not_found = false;
        $family = $db_family;
        break;
      }
    }
    if ($family_not_found) {
      return $this->redirectToRoute('app_shop');
    }
    $db_products = $doctrine->getRepository('TrailWarehouseAppBundle:Product')->findAll();
    $data = [
      'family' => $family,
      'products' => $db_products,
    ];
    return $this->render('TrailWarehouseAppBundle:Shop:family.html.twig', $data);
  }
}
