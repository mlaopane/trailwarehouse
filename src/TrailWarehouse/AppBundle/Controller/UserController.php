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
  protected $repo;

  public function __construct(EntityManagerInterface $em)
  {
    $this->repo = [
      'user'  => $em->getRepository('TrailWarehouseAppBundle:User'),
      'order' => $em->getRepository('TrailWarehouseAppBundle:Order'),
    ];
    $this->user = new User();
  }

  /* -------------- */
  /* *** Routes *** */
  /* -------------- */

  /**
   * 'signup' route
   * @param Request $request
   */
  public function signupAction(Request $request)
  {
    $form = $this->createForm(SignupType::class, $this->user);
    $form->handleRequest($request);
    // Form submitted ?
    if ($form->isSubmitted() AND $form->isValid())
    {
      $manager = $this->getDoctrine()->getManager();
      $manager->persist($this->user);
      $manager->flush();
      return $this->redirectToRoute('app_home');

      /* Auto-login after rrgistration */
      // return $this
      //   ->get('security.authentication.guard_handler')
      //   ->authenticateUserAndHandleSuccess(
      //     $user,
      //     $request,
      //     $this->get('app.security.login_form_authenticator')
      //   );
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
      'user_form'    => $this->createForm(AccountType::class, $user)->createView(),
      'address_form' => $this->createForm(CoordinateType::class, new Coordinate())->createView(),
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


  /* -------------------------- */
  /* *** Additional Methods *** */
  /* -------------------------- */

}
