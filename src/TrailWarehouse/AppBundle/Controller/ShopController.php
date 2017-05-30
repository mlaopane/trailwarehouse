<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
   * @param {string} $slug
   */
  public function familyAction($slug)
  {
    $doctrine = $this->getDoctrine();
    $repository['family'] = $doctrine->getRepository('TrailWarehouseAppBundle:Family');
    $repository['product'] = $doctrine->getRepository('TrailWarehouseAppBundle:Product');

    // Control if the family exists
    $db_family = $repository['family']->findOneBySlug($slug);
    if (empty($db_family)) {
      return $this->redirectToRoute('app_shop');
    }

    // At this point, the family does exist
    $db_product = $repository['product']->getOneRandBy('family', $db_family);
    $data = [
      'family' => $db_family,
      'product' => $db_product,
    ];
    return $this->render('TrailWarehouseAppBundle:Shop:family.html.twig', $data);
  }
}
