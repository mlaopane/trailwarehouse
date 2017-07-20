<?php

namespace TrailWarehouse\AppBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use TrailWarehouse\AppBundle\Entity\Cart;
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

  public function __construct(EntityManagerInterface $em)
  {
    $this->repo = [
      'product'    => $em->getRepository('TrailWarehouseAppBundle:Product'),
      'promo'      => $em->getRepository('TrailWarehouseAppBundle:Promo'),
      'coordinate' => $em->getRepository('TrailWarehouseAppBundle:Coordinate'),
    ];
  }

  /**
   * Route 'app_order_coordinates'
   */
  public function coordinatesAction(Request $request, SessionInterface $session, UserInterface $user)
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
      'addresses' => $this->repo['coordinate']->getBy('user', $user),
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
    $order = (new Order())->setUser($user);
    $form = $this->createForm(OrderType::class, $order, [
      'action'    => $this->generateUrl('app_order_set_address'),
      'user'      => $user,
      'addresses' => $this->repo['coordinate']->getBy('user', $user),
    ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() AND $form->isValid()) {
      $db_coordinate = $this->repo['coordinate']->getOneByArray([
        'id'   => $order->getCoordinate()->getId(),
        'user' => $user
      ]);
      if (empty($db_coordinate)) {
        $this->addFlash('warning', 'Cette adresse ne vous appartient pas');
        return $this->redirectToRoute('app_order_coordinates');
      }

      $session->set('order', $order);
      return $this->redirectToRoute('app_order_payment');
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
  public function createAction(Request $request, SessionInterface $session, EntityManagerInterface $em, UserInterface $user)
  {
    $cart  = $session->get('cart');
    $items = $cart->getItems();
    if (empty($cart) OR $items->count() === 0) {
      return $this->redirectToRoute('app_home');
    }

    $iterator = $items->getIterator();
    $db_promo = $this->repo['promo']->find($cart->getPromo()->getId());
    $today = new \DateTime();
    $order = $session->get('order')
      ->setCreationDate($today)
      ->setSendingDate($today->add(new DateInterval('P1D')))
      ->setPromo($db_promo)
      ->setTotal($cart->getTotal())
    ;

    while($iterator->valid())
    {
      $item = $iterator->current();
      $db_product = $this->repo['product']->find($item->getProduct()->getId());
      if ($db_product->getStock() < $item->getQuantity()) {
        $this->addFlash('failure', "Le produit <span class=\"font-weight-bold\">". $db_product->getName() ."</strong> n'est plus disponible pour la quantité demandée");
        return $this->redirectToRoute('app_cart');
      }
      $order_product = (new OrderProduct())
        ->setOrder($order)
        ->setProduct($db_product)
        ->setQuantity($item->getQuantity())
        ->setTotal($item->getTotal())
      ;
      $em->persist($order_product);
      $iterator->next();
    }

    $em->persist($order);
    $em->flush();
    $cart = new Cart();

    return $this->redirectToRoute('app_account');
  }

}
