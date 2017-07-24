<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Form\Form;
use TrailWarehouse\AppBundle\Entity\User;
use TrailWarehouse\AppBundle\Entity\Cart;
use TrailWarehouse\AppBundle\Entity\Coordinate;
use TrailWarehouse\AppBundle\Form\SignupType;
use TrailWarehouse\AppBundle\Form\SigninType;
use TrailWarehouse\AppBundle\Form\AccountType;
use TrailWarehouse\AppBundle\Form\CoordinateType;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class AccountController extends Controller
{
  /* --------------------- */
  /* *** Initilization *** */
  /* --------------------- */

  protected $repo;

  public function __construct(EntityManagerInterface $em)
  {
    $this->repo = [
      'user'  => $em->getRepository('TrailWarehouseAppBundle:User'),
      'role'  => $em->getRepository('TrailWarehouseAppBundle:Role'),
      'order' => $em->getRepository('TrailWarehouseAppBundle:Order'),
    ];
  }

  /* -------------- */
  /* *** Routes *** */
  /* -------------- */

  /**
   * 'app_account' route
   */
  public function indexAction(Request $request, EntityManagerInterface $em, UserInterface $user)
  {
    $user_form    = $this->handleUserForm($request, $em, $user);
    $address_form = $this->handleAddressForm($request, $em, $user, $address);

    $data = [
      'user_form'    => $user_form->createView(),
      'address_form' => $address_form->createView(),
      'user'         => $user,
      'orders'       => $this->repo['order']->getBy('user', $user),
      'error'        => null,
      'tabs'         => [
        [ 'label' => 'Mon profil', 'class' => 'active' ],
        [ 'label' => 'Mes commandes' ],
        [ 'label' => 'Mes adresses'],
      ],
    ];
    return $this->render('TrailWarehouseAppBundle:User:account.html.twig', $data);
  }

  /**
   * Initialize the user_form and handle the request
   * Update the User if necessary
   *
   * @return Form
   */
  private function handleUserForm(Request $request, EntityManager $em, User $user)
  {
    $form = $this->createForm(AccountType::class, $user, [
      'action' => $this->generateUrl('account_update'),
    ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() AND $form->isValid()) {
      $em->persist($user);
      $em->flush();
    }

    return $form;
  }

  /**
   * Initialize the address_form and handle the request
   * Update the User if necessary
   *
   * @return Form
   */
  private function handleAddressForm(Request $request, EntityManager $em, User $user, Coordinate $address)
  {
    $address = new Coordinate();
    $form = $this->createForm(CoordinateType::class, $address, [
      'action' => $this->generateUrl('account_update'),
    ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() AND $form->isValid()) {
      $em->persist($address->setUser($user));
      $em->flush();
    }

    return $form;
  }

  /* -------------------------- */
  /* *** Additional Methods *** */
  /* -------------------------- */

}
