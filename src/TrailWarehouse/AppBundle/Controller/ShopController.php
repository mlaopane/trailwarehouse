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
use TrailWarehouse\AppBundle\Entity\Family;
use TrailWarehouse\AppBundle\Entity\Category;

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
      'categories'      => $categories,
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
  public function categoryAction(Category $category, $slug) {
    $doctrine = $this->getDoctrine();
    $repo['family'] = $doctrine->getRepository('TrailWarehouseAppBundle:Family');

    $db_families = $repo['family']->getByCategory($category);
    $best = $repo['family']->getBestReviews();

    $data = [
      'active_category' => $category,
      'families' => $db_families,
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
    $doctrine = $this->getDoctrine();
    $entity_names = ['product', 'family'];
    foreach ($entity_names as $entity_name) {
      $repository[$entity_name] = $doctrine->getRepository('TrailWarehouseAppBundle:'.ucfirst($entity_name));
    }
    $colors  = $repository['product']->getColorsByFamily($family);
    $sizes   = $repository['product']->getSizesByFamily($family);
    $data = [
      'family' => $family,
      'colors' => $colors,
      'sizes' => $sizes,
    ];
    return $this->render('TrailWarehouseAppBundle:Shop:family.html.twig', $data);
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

    $repository['product'] = $this->getDoctrine()->getRepository('TrailWarehouseAppBundle:Product');
    $product_exists = !empty($db_product = $repository['product']->find($post_product->id));

    // IF the Product does exist and is available THEN Add Item to Cart
    if ($product_exists AND $db_product->getStock() >= $post_quantity) {
      $new_item = (new Item())
        ->setProduct($db_product)
        ->setQuantity($post_quantity)
        ->setTotal($post_product->price * $post_quantity)
      ;
      // IF the cart doesn't exist
      if (empty($cart = $request->getSession()->get('cart'))) {
        $cart = new Cart();
      }
      // ELSE check if the product already exists in the cart
      else {
        foreach ($cart_items = $cart->getItems() as $cart_item) {
          if ($cart_item->getProduct()->getId() == $db_product->getId()) {
            $cart->removeItem($cart_item);
            break;
          }
        }
      }
      $cart->addItem($new_item);
      $request->getSession()->set('cart', $cart);
      return new JsonResponse($this->serializer->serialize($cart, 'json'));
    }
    return new JsonResponse(array());
  }
}
