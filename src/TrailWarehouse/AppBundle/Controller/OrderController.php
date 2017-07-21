<?php

namespace TrailWarehouse\AppBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use TrailWarehouse\AppBundle\Entity\Cart;
use TrailWarehouse\AppBundle\Entity\Item;
use TrailWarehouse\AppBundle\Entity\User;
use TrailWarehouse\AppBundle\Entity\Promo;
use TrailWarehouse\AppBundle\Entity\Order;
use TrailWarehouse\AppBundle\Entity\Product;
use TrailWarehouse\AppBundle\Entity\Coordinate;
use TrailWarehouse\AppBundle\Entity\OrderProduct;
use TrailWarehouse\AppBundle\Form\OrderType;
use TrailWarehouse\AppBundle\Form\CoordinateType;

class OrderController extends Controller
{

  protected $repo;
  protected $order;
  protected $cart;

  public function __construct(EntityManagerInterface $em, SessionInterface $session)
  {
    /* Init repositories */
    $this->repo = [
      'user'       => $em->getRepository('TrailWarehouseAppBundle:User'),
      'product'    => $em->getRepository('TrailWarehouseAppBundle:Product'),
      'promo'      => $em->getRepository('TrailWarehouseAppBundle:Promo'),
      'coordinate' => $em->getRepository('TrailWarehouseAppBundle:Coordinate'),
    ];

    /* Init Cart IF it exists in the session */
    if (empty($this->cart = $session->get('cart'))) {
      return $this->redirectToRoute('app_cart');
    }

    /* Init Order -> Create a new one if it doesn't exist in the session */
    if (empty($this->order = $session->get('order'))) {
      $this->order = new Order();
      $session->set('order', $this->order);
    }
  }

  /* ----- Routes ----- */

  /**
   * Route 'app_order_coordinates'
   */
  public function coordinatesAction(SessionInterface $session, UserInterface $user)
  {
    if (false === $session->get('checkout')) {
      return $this->redirectToRoute('app_cart');
    }
    $session->set('checkout', false);

    $form['coordinate'] = $this->createForm(CoordinateType::class, new Coordinate(), [
      'action' => $this->generateUrl('app_order_add_address')
    ]);

    $form['order'] = $this->createForm(OrderType::class, new Order(), [
      'action'    => $this->generateUrl('app_order_set_address'),
      'user'      => $user,
      'addresses' => $this->repo['coordinate']->findByUser($user),
    ]);

    $data = [
      'coordinate_form' => $form['coordinate']->createView(),
      'order_form'      => $form['order']->createView(),
    ];

    return $this->render('TrailWarehouseAppBundle:Order:coordinates.html.twig', $data);
  }

  /**
   * Route 'app_order_add_address'
   */
  public function addAddressAction(Request $request, SessionInterface $session, EntityManagerInterface $em, UserInterface $user)
  {
    $coordinate = new Coordinate();
    $form = $this->createForm(CoordinateType::class, $coordinate);
    $form->handleRequest($request);

    if ($form->isSubmitted() AND $form->isValid()) {
      $session->set('checkout', true);
      if ($this->repo['coordinate']->isDoublon($coordinate->setUser($user))) {
        $this->addFlash('warning', "Cette adresse existe déjà/nVeuillez choisir un titre différent");
      }
      else {
        $em->persist($coordinate);
        $em->flush();
        $this->addFlash('success', "Adresse ajoutée !");
        $session->set('checkout', true);
        return $this->redirectToRoute('app_order_coordinates');
      }
    }
    return $this->redirectToRoute('app_cart');
  }

  /**
   * Route 'app_order_set_address'
   */
  public function setAddressAction(Request $request, SessionInterface $session, UserInterface $user)
  {
    $order = $session->get('order');
    $form = $this->createForm(OrderType::class, $order, [
      'action'    => $this->generateUrl('app_order_set_address'),
      'user'      => $user,
      'addresses' => $this->repo['coordinate']->findByUser($user),
    ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() AND $form->isValid())
    {
      $db_coordinate = $this->repo['coordinate']->getOneByArray([
        'id'   => $order->getCoordinate()->getId(),
        'user' => $user
      ]);
      if (empty($db_coordinate)) {
        $this->addFlash('warning', 'Cette adresse ne vous appartient pas');
        return $this->redirectToRoute('app_order_coordinates');
      }
      $session->set('order', $order);
      return $this->redirectToRoute('app_order_create');
    }
  }

  /**
   * Route 'app_order_payment'
   */
  public function paymentAction(Request $request, SessionInterface $session)
  {
    $data = [

    ];
    return $this->render('TrailWarehouseAppBundle:Order:payment.html.twig', $data);
  }

  /**
   * Route 'app_order_create'
   */
  public function createAction(SessionInterface $session, EntityManagerInterface $em, UserInterface $user)
  {
    $cart  = $session->get('cart');
    $order = $session->get('order');

    if (empty($cart) OR $cart->getItems()->count() < 1 OR empty($order->getCoordinate())) {
      return $this->redirectToRoute('app_cart');
    }

    $user = $this->repo['user']->find($user->getId());
    $address = $this->repo['coordinate']->find($order->getCoordinate()->getId());

    $this->persistOrder($order->setUser($user)->setCoordinate($address), $cart, $em);
    if (false === $this->persistOrderProducts($order, $cart, $em)) {
      return $this->redirectToRoute('app_cart');
    }

    $em->flush();
    $this->addFlash('success', 'Votre commande a été finalisée avec succès !');

    return $this->redirectToRoute('app_order_success');
  }

  /**
   * Route 'app_order_success'
   */
  public function successAction(SessionInterface $session)
  {
    $session->remove('order');
    $session->remove('cart');
    return $this->redirectToRoute('app_account');
  }


  /* ----- Utilities ----- */

  /**
   * @return An order initialized from the cart
   */
  private function persistOrder(Order $order, Cart $cart, EntityManagerInterface $em)
  {
    $creation_date = new \DateTime();
    $sending_date = (new \DateTime())->add(new \DateInterval('P1D'));
    $order = $order
      ->setCreationDate($creation_date)
      ->setSendingDate($sending_date)
      ->setPromo($cart->getPromo())
      ->setTotal($cart->getTotal())
    ;
    return $em->persist($order);
  }

  /**
   *
   */
  private function persistOrderProducts(Order $order, Cart $cart, EntityManagerInterface $em)
  {
    $iterator = $cart->getItems()->getIterator();

    while($iterator->valid())
    {
      $item = $iterator->current();
      $db_product = $this->repo['product']->find($item->getProduct()->getId());

      if ($db_product->getStock() < $item->getQuantity()) {
        $this->addFlash('failure', "Le produit <span class=\"font-weight-bold\">". $db_product->getName() ."</strong> n'est plus disponible pour la quantité demandée");
        return false;
      }

      $em->persist($this->createOrderProduct($order, $item));
      $iterator->next();
    }

    return true;
  }

  /**
   * @return An OrderProduct created from an $order and an $item
   */
  private function createOrderProduct(Order $order, Item $item)
  {
    $product   = $this->repo['product']->find($item->getProduct()->getId());
    $quantity  = $item->getQuantity();
    $new_stock = $product->getStock() - $quantity;
    return (new OrderProduct())
      ->setOrder($order)
      ->setProduct($product->setStock($new_stock))
      ->setQuantity($quantity)
      ->setTotal($item->getTotal())
    ;
  }

}
