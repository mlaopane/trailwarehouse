<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
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

class ShopController extends Controller
{

  public function __construct() {
    $normalizer = new ObjectNormalizer();
    $normalizer->setCircularReferenceHandler(function ($object) {
      return $object->getId();
    });
    $normalizers = [ $normalizer ];
    $encoders = [ new JsonEncoder() ];

    $this->serializer = new Serializer($normalizers, $encoders);
  }

  /**
   * 'app_shop'
   */
  public function indexAction() {
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
    $db_families      = $repo['family']->findAll();

    $families = [];
    foreach ($db_families as $db_family) {
      if ($db_family->getProducts()->count() > 0) {
        $families[] = $db_family;
      }
    }

    $data = [
      'active_category' => $category,
      'families'        => $families,
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

  /**
   * 'app_shop_cart'
   *
   */
  public function cartAction(Request $request) {
    $cart_form = $this->createForm(CartType::class, new Cart());
    $promo_form = $this->createForm(PromoType::class, new Promo());
    $data = [
      'cart_form'  => $cart_form->createView(),
      'promo_form' => $promo_form->createView(),
    ];
    return $this->render('TrailWarehouseAppBundle:Shop:cart.html.twig', $data);
  }

  /*
   * Add Item to Cart
   *
   * [POST]
   */
  public function addToCartAction(Request $request) {
    $post_item     = json_decode(file_get_contents('php://input'));
    $post_product  = $post_item->product;
    $post_quantity = $post_item->quantity;

    // IF the quantity isn't a natural number
    if ($post_quantity <= 0) {
      return new JsonResponse(false);
    }

    $repository['product'] = $this->getDoctrine()->getRepository('TrailWarehouseAppBundle:Product');
    $product_not_exists = empty($db_product = $repository['product']->find($post_product->id));

    // IF the Product does NOT exist OR the quantity is NOT available
    if ($product_not_exists OR ($post_quantity > $db_product->getStock())) {
      return new JsonResponse(false);
    }

    // Create the Item
    $new_item = (new Item())
      ->setProduct($db_product)
      ->setQuantity($post_quantity)
      ->setTotal($post_product->price * $post_quantity)
    ;

    // IF the Cart doesn't exist THEN create an new Cart
    if (empty($cart = $request->getSession()->get('cart'))) {
      $cart = new Cart();
    }
    // ELSE
    else {
      foreach ($cart_items = $cart->getItems() as $cart_item) {
        // IF the product already appears in a Cart Item THEN remove the item (before adding the new one)
        if ($cart_item->getProduct()->getId() == $db_product->getId()) {
          $cart->removeItem($cart_item);
          break;
        }
      }
    }

    // Add the new Item and Update the Cart
    $cart->addItem($new_item);
    $request->getSession()->set('cart', $cart);
    return new JsonResponse($this->serializer->serialize($new_item, 'json'));
  }

  public function addPromoAction(Request $request) {
    $post_code = json_decode(file_get_contents('php://input'));
    $repository['Promo'] = $this->getDoctrine()->getRepository('TrailWarehouseAppBundle:Promo');
    if (empty($repository['Promo']->getOneBy($post_code))) {
      return new JsonResponse(false);
    }
    else {

    }
  }

}
