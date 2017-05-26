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
    $doctrine = $this->getDoctrine();
    $repo_brand = $doctrine->getRepository('TrailWarehouseAppBundle:Brand');
    $brands = $repo_brand->findAll(['brand' => 'asc']);
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
    $doctrine = $this->getDoctrine();
    $repo_category = $doctrine->getRepository('TrailWarehouseAppBundle:Category');
    $categories = $repo_category->findAll();
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
    $repo_category = $doctrine->getRepository('TrailWarehouseAppBundle:Category');
    $repo_family = $doctrine->getRepository('TrailWarehouseAppBundle:Family');

    // Toutes les catégories
    if ($category == 'toutes') {
      $db_category['name'] = 'toutes';
      $db_families = $repo_family->findAll();
    }
    // Une catégorie spécifique
    else {
      $db_category = $repo_category->findOneBy(['name' => $category]);
      if (empty($db_category)) {
        return $this->redirectToRoute('app_shop');
      }
      $db_families = $repo_family->getByCategory($db_category);
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
    $repository['family'] = $doctrine->getRepository('TrailWarehouseAppBundle:Family');
    $repository['product'] = $doctrine->getRepository('TrailWarehouseAppBundle:Product');

    // Control if the family exists
    $family_not_found = true;
    $db_families = $repository['family']->findAll();
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

    // At this point, the family does exist
    $db_rand_product = $repository['product']->getOneRand();
    $data = [
      'family' => $family,
      'product' => $db_rand_product,
    ];
    return $this->render('TrailWarehouseAppBundle:Shop:family.html.twig', $data);
  }
}
