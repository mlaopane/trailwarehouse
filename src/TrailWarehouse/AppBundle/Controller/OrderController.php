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
use TrailWarehouse\AppBundle\Form\CoordinateType;

class OrderController extends Controller
{

  protected $repo;

  public function __construct(EntityManagerInterface $em)
  {
    $this->repo = [
      'product' => $em->getRepository('TrailWarehouseAppBundle:Product'),
      'promo'   => $em->getRepository('TrailWarehouseAppBundle:Promo'),
    ];
  }

  /**
   * Route 'app_order_coordinates'
   */
  public function coordinatesAction(Request $request, SessionInterface $session)
  {
    $form = $this->createForm(CoordinateType::class, new Coordinate());
    $form->handleRequest($request);

    if ($form->isSubmitted() AND $form->isValid()) {

    }

    $data = [
      'coordinate_form' => $form->createView(),
    ];
    return $this->render('TrailWarehouseAppBundle:Order:coordintes.html.twig', $data);
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

    $iterator = $cart->getItems()->getIterator();
    $db_promo = $this->repo['promo']->find($cart->getPromo()->getId());
    $order = (new Order())
      ->setUser($user)
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
