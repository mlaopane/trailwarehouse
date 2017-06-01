<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use TrailWarehouse\AppBundle\Entity\Family;
use TrailWarehouse\AppBundle\Entity\Category;

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
    return $this->render('TrailWarehouseAppBundle:Shop:menu.html.twig', $data);
  }

  /**
   * 'app_shop_categories'
   */
  public function categoriesAction()
  {
    $category['name'] = 'toutes';
    $doctrine = $this->getDoctrine();
    $repo['family'] = $doctrine->getRepository('TrailWarehouseAppBundle:Family');

    $db_families = $repo['family']->getAll();

    $data = [
      'active_category' => $category,
      'families' => $db_families,
    ];

    return $this->render('TrailWarehouseAppBundle:Shop:category.html.twig', $data);
  }

  /**
   * 'app_shop_category'
   * @param {string} $slug
   *
   * @ParamConverter("category", options={"mapping": {"slug": "slug"}})
   */
  public function categoryAction(Category $category, $slug)
  {
    $doctrine = $this->getDoctrine();
    $repo['family'] = $doctrine->getRepository('TrailWarehouseAppBundle:Family');

    $db_families = $repo['family']->getByCategory($category);

    $data = [
      'active_category' => $category,
      'families' => $db_families,
    ];

    return $this->render('TrailWarehouseAppBundle:Shop:category.html.twig', $data);
  }

  /**
   * 'app_shop_family'
   * @param Family $family (from slug)
   *
   */
  public function familyAction(Family $family)
  {
    $doctrine = $this->getDoctrine();
    $entity_names = ['product', 'family'];
    foreach ($entity_names as $entity_name) {
      $repository[$entity_name] = $doctrine->getRepository('TrailWarehouseAppBundle:'.ucfirst($entity_name));
    }
    $product = $repository['product']->getOneRandByFamily($family);
    $colors = $repository['product']->getColorsByFamily($family);
    $sizes = $repository['product']->getSizesByFamily($family);
    $data = [
      'family' => $family,
      'product' => $product,
      'colors' => $colors,
      'sizes' => $sizes,
    ];
    return $this->render('TrailWarehouseAppBundle:Shop:family.html.twig', $data);
  }
}
