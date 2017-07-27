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
use TrailWarehouse\AppBundle\Entity\Address;
use TrailWarehouse\AppBundle\Form\SignupType;
use TrailWarehouse\AppBundle\Form\SigninType;
use TrailWarehouse\AppBundle\Form\AccountType;
use TrailWarehouse\AppBundle\Form\AddressType;
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
  public function indexAction(UserInterface $user, Request $request, EntityManagerInterface $em)
  {
    $form = [
      'user'    => $this->handleUserForm($user, $request, $em),
      'address' => $this->handleAddressForm($user, $request, $em),
    ];

    $data = [
      'user_form'    => $form['user']->createView(),
      'address_form' => $form['address']->createView(),
      'user'         => $user,
      'orders'       => $this->repo['order']->getBy('user', $user),
      'error'        => null,
      'tabs'         => [
        [ 'label' => 'Mon profil', 'class' => 'active' ],
        [ 'label' => 'Mes commandes' ],
        [ 'label' => 'Mes adresses'],
      ],
    ];

    return $this->render('TrailWarehouseAppBundle:Account:index.html.twig', $data);
  }

  /**
   * Handle the User Form
   * @return Form
   */
  private function handleUserForm(UserInterface $user, Request $request, EntityManagerInterface $em)
  {
    $form = $this->createForm(AccountType::class, $user);

    $form->handleRequest($request);

    if ($form->isSubmitted() AND $form->isValid())
    {
      $em->persist($user);
      $em->flush();
      $this->addFlash('success', "Vos informations ont été mises à jour");
    }

    return $form;
  }

  /**
   * Handle the Address Form
   * @return Form
   */
  private function handleAddressForm(User $user, Request $request, EntityManagerInterface $em)
  {
    $address = new Address();

    $form = $this->createForm(AddressType::class, $address);

    $form->handleRequest($request);

    if ($form->isSubmitted() AND $form->isValid())
    {
      if ($this->repo['address']->isDoublon($address->setUser($user))) {
        $this->addFlash('warning', "Cette adresse existe déjà/nVeuillez choisir un Libellé différent");
      }
      else {
        $em->persist($address);
        $em->flush();
        $this->addFlash('success', "Adresse ajoutée !");
      }
    }

    return $form;
  }

  /* -------------------------- */
  /* *** Additional Methods *** */
  /* -------------------------- */

}
