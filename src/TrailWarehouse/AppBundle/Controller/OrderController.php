<?php

namespace TrailWarehouse\AppBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Security\Core\User\UserInterface;
use TrailWarehouse\AppBundle\Entity\Cart;
use TrailWarehouse\AppBundle\Entity\Promo;
use TrailWarehouse\AppBundle\Entity\Order;
use TrailWarehouse\AppBundle\Entity\Product;
use TrailWarehouse\AppBundle\Entity\OrderProduct;

class OrderController extends Controller
{
  public function __construct()
  {
    $normalizer = new ObjectNormalizer();
    $normalizer->setCircularReferenceHandler(function ($object) {
      return $object->getId();
    });
    $normalizers = [ $normalizer ];
    $encoders = [ new JsonEncoder() ];
    $this->serializer = new Serializer($normalizers, $encoders);
  }

  /**
   * Route 'app_order'
   */
  public function indexAction(Request $request, SessionInterface $session)
  {
    return $this->render('TrailWarehouseAppBundle:Cart:index.html.twig', $data);
  }

  /**
   * Route 'app_order'
   */
  public function createAction(
    Request $request,
    SessionInterface $session,
    EntityManagerInterface $em,
    UserInterface $user)
  {
    $cart  = $session->get('cart');
    $items = $cart->getItems();
    if (empty($cart) OR $items->count() === 0) {
      return $this->redirectToRoute('app_shop');
    }

    $iterator = $cart->getItems()->getIterator();
    $order = (new Order())
      ->setUser($user)
      ->setTotal($cart->getTotal())
    ;

    while($iterator->valid())
    {
      $item = $iterator->current();
      $order_product = (new OrderProduct())
        ->setOrder($order)
        ->setProduct($item->getProduct())
        ->setQuantity($item->getQuantity())
      ;
      $em->persist($order_product);
    }

    $em->persist($order);
    $em->flush();
    $cart = new Cart();

    return $this->redirectToRoute('app_account');
  }

}
