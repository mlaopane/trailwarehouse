<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Form;
use TrailWarehouse\AppBundle\Entity\User;
use TrailWarehouse\AppBundle\Entity\Address;
use TrailWarehouse\AppBundle\Form\AccountType;
use TrailWarehouse\AppBundle\Form\AddressType;
use TrailWarehouse\AppBundle\Service\RepositoryManager;
use Doctrine\ORM\EntityManagerInterface;

class AccountController extends Controller
{
  /* --------------------- */
  /* *** Initilization *** */
  /* --------------------- */

  protected $repo;
  protected $active_tab = 1;

  public function __construct(RepositoryManager $rm)
  {
    $this->repo = [
      'user'    => $rm->get('User'),
      'address' => $rm->get('Address'),
      'role'    => $rm->get('Role'),
      'order'   => $rm->get('Order'),
    ];
  }

  /* -------------- */
  /* *** Routes *** */
  /* -------------- */

  /**
   * 'app_account' route
   */
  public function indexAction($active_tab = null, UserInterface $user, Request $request, EntityManagerInterface $em)
  {
    if (null == $active_tab) {
      $active_tab = $this->active_tab;
    }
    elseif ($active_tab <= 0 OR $active_tab > 3) {
      return $this->redirectToRoute('app_account');
    }

    $form = [
      'user'    => $this->handleUserForm($user, $request, $em),
      'address' => $this->handleAddressForm($user, $request, $em),
    ];

    $data = [
      'user_form'    => $form['user']->createView(),
      'address_form' => $form['address']->createView(),
      'user'         => $user,
      'orders'       => $this->repo['order']->getBy('user', $user),
      'active_tab'   => $active_tab,
    ];

    $data['tabs'] = [
      [ 'label' => 'Mes commandes', 'class' => ($active_tab == 1 ? 'active' : ''), 'class_pane' => ($active_tab == 1 ? 'show active' : '') ],
      [ 'label' => 'Mon profil', 'class' => ($active_tab == 2 ? 'active' : ''), 'class_pane' => ($active_tab == 2 ? 'show active' : '') ],
      [ 'label' => 'Mes adresses', 'class' => ($active_tab == 3 ? 'active' : ''), 'class_pane' => ($active_tab == 3 ? 'show active' : '') ],
    ];

    return $this->render('TrailWarehouseAppBundle:Account:index.html.twig', $data);
  }

  /**
   * 'app_account_remove_address'
   * @ParamConverter("address", options={"mapping": {"address_id": "id"}})
   */
  public function removeAddressAction(Address $address, UserInterface $user, Request $request, EntityManagerInterface $em)
  {
    $db_address = $this->repo['address']->findOneBy(['id' => $address->getId(), 'user' => $user]);
    if (empty($db_address)) {
      $this->addFlash('type', 'Opération non autorisée');
    }
    else {
      $em->remove($db_address);
      $em->flush();
      $this->addFlash('success', 'Adresse supprimée');
    }
    return $this->redirectToRoute('app_account', ['active_tab' => 3]);
  }

  /**
   * Handle the User Form
   * @return Form
   */
  private function handleUserForm(UserInterface $user, Request $request, EntityManagerInterface $em)
  {
    $form = $this->createForm(AccountType::class, $user, [
      'action' => $this->generateUrl('app_account', [ 'active_tab' => 2 ])
    ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() AND $form->isValid())
    {
      $em->persist($user);
      $em->flush();
      $this->addFlash('success', "Vos informations ont été mises à jour");
      $this->active_tab = 2;
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

    $form = $this->createForm(AddressType::class, $address, [
      'action' => $this->generateUrl('app_account', [ 'active_tab' => 3 ])
    ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() AND $form->isValid())
    {
      if ($this->repo['address']->isDoublon($address->setUser($user))) {
        $this->addFlash('warning', "Cette adresse existe déjà !<br>Veuillez choisir un Libellé différent");
      }
      else {
        $em->persist($address);
        $em->flush();
        $this->addFlash('success', "Adresse ajoutée !");
      }
      $this->active_tab = 3;
    }


    return $form;
  }

  /* -------------------------- */
  /* *** Additional Methods *** */
  /* -------------------------- */

}
