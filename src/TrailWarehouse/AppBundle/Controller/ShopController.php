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
use Doctrine\ORM\EntityManagerInterface;

class ShopController extends Controller
{

  protected $repo;
  protected $serializer;

  public function __construct(SessionInterface $session, EntityManagerInterface $em)
  {
    $this->repo = [
      'brand'    => $em->getRepository('TrailWarehouseAppBundle:Brand'),
      'category' => $em->getRepository('TrailWarehouseAppBundle:Category'),
      'family'   => $em->getRepository('TrailWarehouseAppBundle:Family'),
      'product'  => $em->getRepository('TrailWarehouseAppBundle:Product'),
    ];

    $normalizer = new ObjectNormalizer();
    $normalizer->setCircularReferenceHandler(function ($object) {
      return $object->getId();
    });
    $normalizers = [ $normalizer ];
    $encoders    = [ new JsonEncoder() ];
    $this->serializer = new Serializer($normalizers, $encoders);

    if (empty($cart = $session->get('cart'))) {
      $session->set('cart', new Cart());
    }
  }

  /**
   * 'app_home'
   */
  public function indexAction()
  {
    return $this->redirectToRoute('app_shop_categories');
  }

  /**
   * Gets the categories then render the menu
   */
  public function menuAction($active_category = NULL) {
    $data = [
      'categories'      => $this->repo['category']->findAll(['category' => 'asc']),
      'active_category' => $active_category,
    ];
    return $this->render('TrailWarehouseAppBundle:Shop:menu.html.twig', $data);
  }

  /**
   * 'app_shop_categories'
   */
  public function categoriesAction()
  {
    $data = [
      'active_category' => ['name' => 'toutes'],
      'families'        => $this->repo['family']->getAll(),
    ];

    return $this->render('TrailWarehouseAppBundle:Shop:category.html.twig', $data);
  }

  /**
   * 'app_shop_category'
   *
   * @ParamConverter("category", options={"mapping": {"slug": "slug"}})
   */
  public function categoryAction(Category $category, string $slug, EntityManagerInterface $em)
  {
    $data = [
      'active_category' => $category,
      'families'        => $this->repo['family']->getByCategory($category),
    ];

    return $this->render('TrailWarehouseAppBundle:Shop:category.html.twig', $data);
  }

  /**
   * 'app_shop_family'
   * @param Family $family (from slug)
   *
   */
  public function familyAction(Family $family, EntityManagerInterface $em)
  {
    if ($family->getProducts()->count() == 0) {
      return $this->redirectToRoute('app_home');
    }

    $data = [
      'family' => $family,
      'colors' => $this->repo['product']->getColorsByFamily($family),
      'sizes'  => $this->repo['product']->getSizesByFamily($family),
    ];

    return $this->render('TrailWarehouseAppBundle:Shop:family.html.twig', $data);
  }

}
