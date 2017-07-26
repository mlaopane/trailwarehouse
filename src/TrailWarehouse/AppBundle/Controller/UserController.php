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
      'role'  => $em->getRepository('TrailWarehouseAppBundle:Role'),
    ];
    $this->user = new User($this->repo['role']->findOneByName('ROLE_USER'));
  }

  /* -------------- */
  /* *** Routes *** */
  /* -------------- */

  /**
   * 'signup' route
   * @param Request $request
   */
  public function signupAction(Request $request, EntityManagerInterface $em)
  {
    $form = $this->createForm(SignupType::class, $this->user);
    $form->handleRequest($request);

    if ($form->isSubmitted() AND $form->isValid())
    {
      if ($this->user->getEmail() == 'mlaopane@gmail.com') {
        $this->user
          ->setRole($this->repo['role']->findOneByName('ROLE_SUPER_ADMIN'));
      }
      $em->persist($this->user);
      $em->flush();
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
  public function signinAction(Request $request, AuthenticationUtils $authUtils, UserInterface $user = null)
  {
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
  public function signoutAction(Request $request)
  {
    // Return
    return $this->render('TrailWarehouseAppBundle:Home:index.html.twig');
  }


  /* -------------------------- */
  /* *** Additional Methods *** */
  /* -------------------------- */

}
