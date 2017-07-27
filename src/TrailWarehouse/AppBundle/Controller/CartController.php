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
      'vat' => $em->getRepository('TrailWarehouseAppBundle:Vat'),
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
      $vat = $this->repo['vat']->findOneByCountry('fr');
      $session->set('cart', (new Cart())->setVat($vat));
    }
  }

  /**
   * 'app_cart'
   */
  public function indexAction(SessionInterface $session)
  {
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
    $db_product = $this->repo['product']->find($post_item->product_id);
    $item = new Item($db_product, $post_item->quantity);

    if (!$this->isCartable($item)) {
      return $this->json(false);
    }

    $session->set('cart', $this->addOrReplaceCartItem($session->get('cart'), $item));
    return $this->json(true);
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
    if (!empty($cart = $session->get('cart')) AND $request->isMethod('POST'))
    {
      if (!empty($_POST['hidden-product-id']) AND !empty($_POST['hidden-quantity']))
      {
        if (empty($db_product = $this->repo['product']->getOne((int) $_POST['hidden-product-id'], false))) {
          $this->addFlash('failure', "Erreur inattendue : le produit ne peut être mis à jour");
        }
        else {
          if ($db_product->getStock() < $_POST['hidden-quantity']) {
            $item = new Item($db_product, $db_product->getStock());
            $this->addFlash('warning',
              "Quantité indisponible !<br>
              Le panier a été mis à jour en conséquence"
            );
          }
          else {
            $item = new Item($db_product, $_POST['hidden-quantity']);
            $this->addFlash('success', "Votre panier a été mis à jour");
          }
          $new_cart = $this->addOrReplaceCartItem($cart, $item);
          $session->set('cart', $new_cart);
        }
      }
    }
    return $this->redirectToRoute('app_cart');
  }

  public function removeItemAction(Request $request, SessionInterface $session)
  {
    if (!empty($cart = $session->get('cart')) AND $request->isMethod('POST'))
    {
      if (!empty($_POST['hidden-product-id']) AND !empty($_POST['hidden-quantity']))
      {
        if (empty($db_product = $this->repo['product']->find($_POST['hidden-product-id']))) {
          $this->addFlash('failure', "Erreur inattendue : le produit ne peut être supprimé");
        }
        else {
          $item = new Item($db_product, $_POST['hidden-quantity']);
          if (!empty($cart_item = $this->findCartItem($cart, $item))) {
            $cart->removeItem($cart_item);
            $session->set('cart', $cart);
          }
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
        $this->addFlash('cart_warning',
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
   * Seek for an Item in the provided Cart and returns it if found
   * @return Cart|NULL
   */
  private function findCartItem(Cart $cart, Item $search_item)
  {
    foreach ($cart_items = $cart->getItems() as $cart_item) {
      if ($cart_item->getProduct()->getId() == $search_item->getProduct()->getId()) {
        return $cart_item;
      }
    }
    return NULL;
  }

  /**
   * Return the updated Cart
   */
  private function addOrReplaceCartItem(Cart $cart, Item $new_item) : Cart
  {
    // Remove the item from the Cart if it does exist
    if (!empty($cart) AND !empty($cart_item = $this->findCartItem($cart, $new_item))) {
      $cart->removeItem($cart_item);
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
