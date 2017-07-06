<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use TrailWarehouse\AppBundle\Entity\Item;
use TrailWarehouse\AppBundle\Entity\Cart;
use TrailWarehouse\AppBundle\Entity\Promo;
use TrailWarehouse\AppBundle\Entity\Family;
use TrailWarehouse\AppBundle\Entity\Category;
use TrailWarehouse\AppBundle\Form\CartType;
use TrailWarehouse\AppBundle\Form\PromoType;
use TrailWarehouse\AppBundle\Controller\CartController;

class ShopController extends Controller
{

  public function __construct(SessionInterface $session) {
    $normalizer = new ObjectNormalizer();
    $normalizer->setCircularReferenceHandler(function ($object) {
      return $object->getId();
    });
    $normalizers = [ $normalizer ];
    $encoders = [ new JsonEncoder() ];
    $this->serializer = new Serializer($normalizers, $encoders);

    if (empty($cart = $session->get('cart'))) {
      $session->set('cart', new Cart());
    }
  }

  /**
   * 'app_shop'
   */
  public function indexAction() {
    $doctrine   = $this->getDoctrine();
    $repo_brand = $doctrine->getRepository('TrailWarehouseAppBundle:Brand');
    $brands     = $repo_brand->findAll(['brand' => 'asc']);

    $data['brands'] = $brands;

    return $this->render('TrailWarehouseAppBundle:Shop:index.html.twig', $data);
  }

  /**
   * Gets the categories then render the menu
   */
  public function menuAction($active_category = NULL) {
    $doctrine = $this->getDoctrine();
    $repo_category = $doctrine->getRepository('TrailWarehouseAppBundle:Category');
    $categories = $repo_category->findAll();
    $data = [
      'categories'      => $categories,
      'active_category' => $active_category,
    ];
    return $this->render('TrailWarehouseAppBundle:Shop:menu.html.twig', $data);
  }

  /**
   * 'app_shop_categories'
   */
  public function categoriesAction() {
    $doctrine         = $this->getDoctrine();
    $category['name'] = 'toutes';
    $repo['family']   = $doctrine->getRepository('TrailWarehouseAppBundle:Family');
    $db_families      = $repo['family']->getAll();

    $data = [
      'active_category' => $category,
      'families'        => $db_families,
    ];

    return $this->render('TrailWarehouseAppBundle:Shop:category.html.twig', $data);
  }

  /**
   * 'app_shop_category'
   * @param {string} $slug
   *
   * @ParamConverter("category", options={"mapping": {"slug": "slug"}})
   */
  public function categoryAction(Category $category, $slug) {
    $doctrine = $this->getDoctrine();
    $repo['family'] = $doctrine->getRepository('TrailWarehouseAppBundle:Family');

    $db_families = $repo['family']->findByCategory($category);
    $families = [];
    foreach ($db_families as $db_family) {
      if ($db_family->getProducts()->count() > 0) {
        $families[] = $db_family;
      }
    }
    $best = $repo['family']->getBestReviews();
    $data = [
      'active_category' => $category,
      'families' => $families,
      'best' => $best,
    ];

    return $this->render('TrailWarehouseAppBundle:Shop:category.html.twig', $data);
  }

  /**
   * 'app_shop_family'
   * @param Family $family (from slug)
   *
   */
  public function familyAction(Family $family) {
    if ($family->getProducts()->count() == 0) {
      return $this->redirectToRoute('app_shop');
    }

    $doctrine = $this->getDoctrine();
    $entity_names = ['product', 'family'];

    foreach ($entity_names as $entity_name) {
      $repository[$entity_name] = $doctrine->getRepository('TrailWarehouseAppBundle:'.ucfirst($entity_name));
    }

    $colors = $repository['product']->getColorsByFamily($family);
    $sizes  = $repository['product']->getSizesByFamily($family);

    $data = [
      'family' => $family,
      'colors' => $colors,
      'sizes' => $sizes,
    ];
    return $this->render('TrailWarehouseAppBundle:Shop:family.html.twig', $data);
  }

}
