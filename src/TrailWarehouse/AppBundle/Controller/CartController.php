<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use TrailWarehouse\AppBundle\Entity\Item;
use TrailWarehouse\AppBundle\Entity\Cart;
use TrailWarehouse\AppBundle\Entity\Promo;
use TrailWarehouse\AppBundle\Entity\Product;
use TrailWarehouse\AppBundle\Form\CartType;
use TrailWarehouse\AppBundle\Form\ItemType;
use TrailWarehouse\AppBundle\Form\PromoType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Collections\ArrayCollection;

class CartController extends Controller
{

  protected $repo;

  public function __construct(SessionInterface $session, EntityManagerInterface $em) {
    $this->repo = [
      'product' => $em->getRepository('TrailWarehouseAppBundle:Product'),
      'promo' => $em->getRepository('TrailWarehouseAppBundle:Promo'),
    ];

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
   * 'app_cart'
   */
  public function indexAction(SessionInterface $session)
  {
    // Promo Form
    $promo_form = $this->createForm(PromoType::class, new Promo(), [
      'action' => $this->generateUrl('app_cart_add_promo'),
    ]);

    $flashbag = $session->getFlashbag();

    $data = [
      'promo_form'   => $promo_form->createView(),
      'promo_errors' => $flashbag->get('promo_errors'),
      'cart_errors'  => $flashbag->get('cart_errors'),
    ];

    return $this->render('TrailWarehouseAppBundle:Cart:index.html.twig', $data);
  }

  /*
   * Add Item to Cart
   * Route 'app_cart_add_item'
   */
  public function addItemAction(SessionInterface $session, EntityManagerInterface $em)
  {
    $post_item = json_decode(file_get_contents('php://input'));
    $db_product = $this->repo['product']->find($post_item->product->id);
    $new_item = new Item($db_product, $post_item->quantity);

    if (!$this->isCartable($new_item)) {
      return $this->json(false);
    }
    if (empty ($cart = $session->get('cart'))) {
      $cart = new Cart();
      $cart->addItem($new_item);
    }
    else {
      $cart = $this->getUpdatedCart($cart, $new_item);
    }
    return $this->json($this->serializer->serialize($new_item, 'json'));
  }

  /**
   * Apply a promo code to the Cart
   * Route 'app_cart_add_promo'
   */
  public function addPromoAction(Request $request, SessionInterface $session, EntityManagerInterface $em)
  {
    // Check if there is a cart to apply the promo code
    if (!empty($cart = $session->get('cart'))) {
      $form = $this->createForm(PromoType::class, new Promo())->handleRequest($request);
      // Check Form
      if ($form->isSubmitted() AND $form->isValid()) {
        // Search for a matching Promo in DB
        $promo = $this->repo['promo']->findOneByCode($form->getData()->getCode());

        // Check Promo and apply if OK
        if (empty($promo)) {
          $this->addFlash('failure', 'Code non valide');
        }
        elseif (!$promo->isActive()) {
          $this->addFlash('failure', 'Le Code <span class="font-weight-bold">'.$promo->getCode().'</span> est expiré');
        }
        else {
          $cart->setPromo($promo)->updateTotal();
        }
      }
    }
    return $this->redirectToRoute('app_cart');
  }

  public function updateItemAction(Request $request, SessionInterface $session)
  {
    if (!empty($cart = $session->get('cart')))
    {
      $cart_item = new Item();
      $form = $this->createForm(ItemType::class, $item);

      if (empty($product)) {
        $this->addFlash('danger', "Erreur inattendue : le produit ne peut être supprimé");
      }
      else {
        if (empty($item)) {
          $this->addFlash('warning', "Le produit " . $product->getName() . " n'existe plus dans votre panier");
        }
        else {
          $new_item = (new Item())
            ->setProduct($product)
            ->setQuantity($quantity)
          ;
          if (!$this->isCartable($new_item)) {
            $this->addFlash('warning', "Quantité indisponible pour " . $product->getName());
          }
          else {
            $cart = $this->getUpdatedCart($cart, $new_item);
            $session->set('cart', $cart);
            $this->addFlash('success', "La quantité pour " . $product->getName() . " a été mise à jour");
          }
        }
      }
    }
  }

  public function removeItemAction(Request $request, SessionInterface $session)
  {
    if (!empty($cart = $session->get('cart')))
    {
      $cart_item = new Item();
      $form = $this->createFormBuilder($cart_item)
        ->add('product_id', HiddenType::class, ['mapped' => false])
        ->add('quantity', TextType::class)
        ->add('total', HiddenType::class)
        ->getForm()
      ;
      if ($request->isMethod('POST') OR $request->isMethod('DELETE'))
      {
        $form->get('product_id')->submit('product_id');
        $product_id = $form->get('product_id');
        dump($product_id);
        die();
      }

      if (empty($product)) {
        $this->addFlash('danger', "Erreur inattendue : le produit ne peut être supprimé");
      }
      else {
        $item = $this->findCartItem($cart, $product);
        if (empty($item)) {
          $this->addFlash('warning', "Le produit " . $product->getName() . " n'existe pas dans votre panier");
        }
        else {
          $this->addFlash('success', "Le produit " . $product->getName() . " a été supprimé");
          $cart->removeItem($item);
          $session->set('cart', $cart);
        }
      }
    }
    return $this->redirectToRoute('app_cart');
  }

  /**
   * Control the cart before checkout
   * Route 'app_cart_checkout'
   */
  public function checkoutAction(SessionInterface $session, EntityManagerInterface $em)
  {
    $repo['product'] = $em->getRepository('TrailWarehouseAppBundle:Product');
    $cart = $session->get('cart');
    $iterator = $cart->getItems()->getIterator();

    while ($iterator->valid())
    {
      $item = $iterator->current();
      $db_product = $repo['product']->getOne($item->getProduct()->getId(), false);
      $db_stock = $db_product->getStock();

      if ($db_stock < $item->getQuantity())
      {
        $this->addFlash(
          'cart_warning',
          "Au moins l'un des produits n'est plus disponible pour la quantité demandée.<br>
          Votre panier a été mis à jour automatiquement"
        );
        $this->getUpdatedCart($cart, new Item($db_product, $db_stock));
        return $this->redirectToRoute('app_cart');
      }

      $iterator->next();
    }

    $session->set('checkout', true);
    return $this->redirectToRoute('app_order_address');
  }

  /* ---------- Private methods ---------- */

  /**
   * Return a new empty or an updated one if it already exists
   */
  private function getUpdatedCart(Cart $cart, Item $new_item) : Cart
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
  private function isCartable(Item $item) : bool
  {
    if ($item_quantity = $item->getQuantity() <= 0) {
      return false;
    }
    $product_exists = !empty($item_product = $item->getProduct());
    $quantity_available = $item_quantity <= $item_product->getStock();
    return $product_exists AND $quantity_available;
  }

}
