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

class CartController extends Controller
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
   * 'app_shop_cart'
   */
  public function indexAction(Request $request) {
    $cart_form = $this->createForm(CartType::class, new Cart());
    $promo_form = $this->createForm(PromoType::class, new Promo());
    $data = [
      'cart_form'  => $cart_form->createView(),
      'promo_form' => $promo_form->createView(),
    ];
    return $this->render('TrailWarehouseAppBundle:Cart:index.html.twig', $data);
  }

  /*
   * Add Item to Cart
   *
   * [POST]
   */
  public function addAction(Request $request) {
    $post_item = json_decode(file_get_contents('php://input'));
    $repository['product'] = $this->getDoctrine()->getRepository('TrailWarehouseAppBundle:Product');
    $db_product = $repository['product']->find($post_item->product->id);
    $new_item  = new Item($db_product, $post_item->quantity);

    if (!$this->isCartable($new_item)) {
      return new JsonResponse(false);
    }
    if (empty ($cart = $request->getSession()->get('cart'))) {
      $cart = new Cart();
    }
    else {
      $cart = $this->updateCart($cart, $new_item);
    }
    $request->getSession()->set('cart', $cart);
    return new JsonResponse($this->serializer->serialize($new_item, 'json'));
  }

  /**
   * Apply a promo code to the Cart
   */
  public function addPromoAction(Request $request) {
    $post_code = json_decode(file_get_contents('php://input'));
    $repository['Promo'] = $this->getDoctrine()->getRepository('TrailWarehouseAppBundle:Promo');
    if (empty($repository['Promo']->getOneBy($post_code))) {
      return new JsonResponse(false);
    }
    else {

    }
  }

  /* ---------- Private methods ---------- */

  /**
   * Return a new empty or an updated one if it already exists
   */
  private function updateCart(Cart $cart, Item $new_item) : Cart {
    // Create a new Cart
    if (empty($cart)) {
      $cart = new Cart();
    }
    // Remove the item from the Cart if it does exist
    else {
      foreach ($cart_items = $cart->getItems() as $cart_item) {
        if ($cart_item->getProduct()->getId() == $new_item->getProduct()->getId()) {
          $cart->removeItem($cart_item);
          break;
        }
      }
    }
    return $cart->addItem($new_item);
  }

  /**
   * Check if an item is valid for being put into the cart
   */
  private function isCartable(Item $item) : bool {
    if ($item_quantity = $item->getQuantity() <= 0) {
      return false;
    }
    $product_exists = !empty($item_product = $item->getProduct());
    $quantity_available = $item_quantity <= $item_product->getStock();
    return $product_exists AND $quantity_available;
  }
}
