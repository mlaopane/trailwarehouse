<?php

namespace TrailWarehouse\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use TrailWarehouse\AppBundle\Entity\User;
use TrailWarehouse\AppBundle\Form\SignupType;
use TrailWarehouse\AppBundle\Form\SigninType;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;

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
  public function signupAction(Request $request)
  {
    $form = $this->createForm(SignupType::class, $this->user);
    $form->handleRequest($request);
    // Form submitted ?
    if ($form->isSubmitted() AND $form->isValid()) {
      // Super Admin ?
      if ($this->user->getEmail() == 'mlaopane@gmail.com') {
        $this->user->setRole('ROLE_SUPER_ADMIN');
      }
      $manager = $this->getDoctrine()->getManager();
      $manager->persist($this->user);
      $manager->flush();
      // Redirect to the Shop
      return $this->redirectToRoute('app_shop');
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
  public function signinAction(Request $request, AuthenticationUtils $authUtils)
  {
    $form = $this->createForm(SigninType::class, $this->user);

    $error     = $authUtils->getLastAuthenticationError();
    $lastEmail = $authUtils->getLastUsername();

    $data = [
      'signin_form' => $form->createView(),
      'last_email'  => $lastEmail,
      'error'       => $error,
    ];
    return $this->render('TrailWarehouseAppBundle:User:signin.html.twig', $data);
  }

  /**
   * 'signout' route
   */
  public function signoutAction(Request $request)
  {
    // Return
    return $this->render('TrailWarehouseAppBundle:Home:index.html.twig');
  }

  /* -------------------------- */
  /* *** Additional Methods *** */
  /* -------------------------- */

}