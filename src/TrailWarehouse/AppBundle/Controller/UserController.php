<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use TrailWarehouse\AppBundle\Entity\User;
use TrailWarehouse\AppBundle\Entity\Cart;
use TrailWarehouse\AppBundle\Entity\Coordinate;
use TrailWarehouse\AppBundle\Form\SignupType;
use TrailWarehouse\AppBundle\Form\SigninType;
use TrailWarehouse\AppBundle\Form\AccountType;
use TrailWarehouse\AppBundle\Form\CoordinateType;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends Controller
{
  /* --------------------- */
  /* *** Initilization *** */
  /* --------------------- */

  protected $user;

  public function __construct() {
    $this->user = new User();
  }

  /* -------------- */
  /* *** Routes *** */
  /* -------------- */

  /**
   * 'signup' route
   * @param Request $request
   */
  public function signupAction(Request $request) {
    $form = $this->createForm(SignupType::class, $this->user);
    $form->handleRequest($request);
    // Form submitted ?
    if ($form->isSubmitted() AND $form->isValid()) {
      // Super Admin ?
      if ($this->user->getEmail() == 'mlaopane@gmail.com') {
        $this->user->setRole('ROLE_SUPER_ADMIN');
      }
      // Admin ?
      if ($this->user->getEmail() == 'mykel.chang@gmail.com') {
        $this->user->setRole('ROLE_ADMIN');
      }
      $manager = $this->getDoctrine()->getManager();
      $manager->persist($this->user);
      $manager->flush();
      // Redirect to the Shop
      return $this->redirectToRoute('app_home');
    }
    // Display the form to sign up
    $data = [
      'signup_form' => $form->createView(),
    ];
    return $this->render('TrailWarehouseAppBundle:User:signup.html.twig', $data);
  }

  /**
   * 'signin' route
   */
  public function signinAction(Request $request, AuthenticationUtils $authUtils, UserInterface $user = null) {

    if ($user) {
      return $this->redirectToRoute('app_account');
    }

    $form = $this->createForm(SigninType::class, $this->user);
    $error        = $authUtils->getLastAuthenticationError();
    $lastUsername = $authUtils->getLastUsername();

    $data = [
      'signin_form'   => $form->createView(),
      'last_username' => $lastUsername,
      'error'         => $error,
    ];
    return $this->render('TrailWarehouseAppBundle:User:signin.html.twig', $data);
  }

  /**
  * 'signout' route
  */
  public function signoutAction(Request $request) {
    // Return
    return $this->render('TrailWarehouseAppBundle:Home:index.html.twig');
  }

  /**
   * 'account' route
   */
  public function accountAction(Request $request, UserInterface $user)
  {

    $data = [
      'user_form'    => $this->createForm(AccountType::class, $this->user)->createView(),
      'address_form' => $this->createForm(CoordinateType::class, new Coordinate())->createView(),
      'user'         => $user,
      'error'        => null,
    ];
    return $this->render('TrailWarehouseAppBundle:User:account.html.twig', $data);
  }


  /* -------------------------- */
  /* *** Additional Methods *** */
  /* -------------------------- */

}
