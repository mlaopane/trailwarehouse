<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use TrailWarehouse\AppBundle\Entity\Item;
use TrailWarehouse\AppBundle\Entity\Cart;
use TrailWarehouse\AppBundle\Entity\Promo;
use TrailWarehouse\AppBundle\Form\CartType;
use TrailWarehouse\AppBundle\Form\PromoType;
use Doctrine\ORM\EntityManagerInterface;

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
  public function indexAction(SessionInterface $session)
  {
    // Promo Form
    $promo_form = $this->createForm(PromoType::class, new Promo(), [
      'action' => $this->generateUrl('app_cart_add_promo'),
    ]);

    // Cart Form
    $cart_form = $this->createForm(CartType::class, new Cart(), [
      'action' => $this->generateUrl('app_cart_check'),
    ]);

    // IF no Form has been submitted
    $flashbag = $session->getFlashbag();
    $data = [
      'cart_form'    => $cart_form->createView(),
      'promo_form'   => $promo_form->createView(),
      'promo_errors' => $flashbag->get('promo_errors'),
      'cart_errors'  => $flashbag->get('cart_errors'),
    ];

    return $this->render('TrailWarehouseAppBundle:Cart:index.html.twig', $data);
  }

  /*
   * Add Item to Cart
   *
   * [POST]
   */
  public function addItemAction(SessionInterface $session)
  {
    $post_item = json_decode(file_get_contents('php://input'));
    $repository['product'] = $this->getDoctrine()->getRepository('TrailWarehouseAppBundle:Product');
    $db_product = $repository['product']->find($post_item->product->id);
    $new_item = new Item($db_product, $post_item->quantity);

    if (!$this->isCartable($new_item)) {
      return $this->json(false);
    }
    if (empty ($cart = $session->get('cart'))) {
      $cart = new Cart();
    }
    $cart = $this->updateCart($cart, $new_item);
    $session->set('cart', $cart);
    return $this->json($this->serializer->serialize($new_item, 'json'));
  }

  /**
   * Apply a promo code to the Cart
   */
  public function addPromoAction(Request $request)
  {
    // Check if there is a cart to apply the promo code
    if (!empty($cart = $request->getSession()->get('cart'))) {
      $form = $this->createForm(PromoType::class, new Promo());
      $form->handleRequest($request);
      // Check Form
      if ($form->isSubmitted() AND $form->isValid()) {
        // Search for a matching Promo in DB
        $repository['promo'] = $this->getDoctrine()->getRepository('TrailWarehouseAppBundle:Promo');
        $post_promo = $form->getData();
        $promo = $repository['promo']->findOneByCode($post_promo->getCode());

        // Check Promo and apply if OK
        if (empty($promo)) {
          $this->addFlash('failure', 'Code non valide');
        }
        elseif (!$promo->isActive()) {
          $this->addFlash('failure', 'Le Code <span class="font-weight-bold">'.$promo->getCode().'</span> est expiré');
        }
        else {
          $cart->setPromo($promo);
          $cart->updateTotal();
          $this->addFlash('success', 'Code <span class="font-weight-bold">'.$promo->getCode().'</span> appliqué');
        }
      }
    }
    return $this->redirectToRoute('app_cart');
  }

  public function checkAction(EntityManagerInterface $manager)
  {
    return $this->redirectToRoute('app_cart');
  }

  /* ---------- Private methods ---------- */

  /**
   * Return a new empty or an updated one if it already exists
   */
  private function updateCart(Cart $cart, Item $new_item) : Cart
  {
    // Remove the item from the Cart if it does exist
    foreach ($cart_items = $cart->getItems() as $cart_item) {
      if ($cart_item->getProduct()->getId() == $new_item->getProduct()->getId()) {
        $cart->removeItem($cart_item);
        break;
      }
    }
    $cart->addItem($new_item)->updateTotal();
    return $cart;
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
